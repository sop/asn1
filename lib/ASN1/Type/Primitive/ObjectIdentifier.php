<?php
declare(strict_types = 1);

namespace ASN1\Type\Primitive;

use ASN1\Element;
use ASN1\Component\Identifier;
use ASN1\Component\Length;
use ASN1\Exception\DecodeException;
use ASN1\Feature\ElementBase;
use ASN1\Type\PrimitiveType;
use ASN1\Type\UniversalClass;

/**
 * Implements <i>OBJECT IDENTIFIER</i> type.
 */
class ObjectIdentifier extends Element
{
    use UniversalClass;
    use PrimitiveType;
    
    /**
     * Object identifier in dotted format.
     *
     * @var string
     */
    protected $_oid;
    
    /**
     * Object identifier split to sub ID's.
     *
     * @var \GMP[]
     */
    protected $_subids;
    
    /**
     * Constructor.
     *
     * @param string $oid OID in dotted format
     */
    public function __construct(string $oid)
    {
        $this->_oid = $oid;
        $this->_subids = self::_explodeDottedOID($oid);
        // if OID is non-empty
        if (count($this->_subids) > 0) {
            // check that at least two nodes are set
            if (count($this->_subids) < 2) {
                throw new \UnexpectedValueException(
                    "OID must have at least two nodes.");
            }
            // check that root arc is in 0..2 range
            if ($this->_subids[0] > 2) {
                throw new \UnexpectedValueException(
                    "Root arc must be in range of 0..2.");
            }
            // if root arc is 0 or 1, second node must be in 0..39 range
            if ($this->_subids[0] < 2 && $this->_subids[1] >= 40) {
                throw new \UnexpectedValueException(
                    "Second node must be in 0..39 range for root arcs 0 and 1.");
            }
        }
        $this->_typeTag = self::TYPE_OBJECT_IDENTIFIER;
    }
    
    /**
     * Get OID in dotted format.
     *
     * @return string
     */
    public function oid(): string
    {
        return $this->_oid;
    }
    
    /**
     *
     * {@inheritdoc}
     */
    protected function _encodedContentDER(): string
    {
        $subids = $this->_subids;
        // encode first two subids to one according to spec section 8.19.4
        if (count($subids) >= 2) {
            $num = ($subids[0] * 40) + $subids[1];
            array_splice($subids, 0, 2, array($num));
        }
        return self::_encodeSubIDs(...$subids);
    }
    
    /**
     *
     * {@inheritdoc}
     * @return self
     */
    protected static function _decodeFromDER(Identifier $identifier,
        string $data, int &$offset): ElementBase
    {
        $idx = $offset;
        $len = Length::expectFromDER($data, $idx)->intLength();
        $subids = self::_decodeSubIDs(substr($data, $idx, $len));
        $idx += $len;
        // decode first subidentifier according to spec section 8.19.4
        if (isset($subids[0])) {
            if ($subids[0] < 80) {
                list($x, $y) = gmp_div_qr($subids[0], "40");
            } else {
                $x = gmp_init(2, 10);
                $y = $subids[0] - 80;
            }
            array_splice($subids, 0, 1, array($x, $y));
        }
        $offset = $idx;
        return new self(self::_implodeSubIDs(...$subids));
    }
    
    /**
     * Explode dotted OID to an array of sub ID's.
     *
     * @param string $oid OID in dotted format
     * @return \GMP[] Array of GMP numbers
     */
    protected static function _explodeDottedOID(string $oid): array
    {
        $subids = [];
        if (strlen($oid)) {
            foreach (explode(".", $oid) as $subid) {
                $n = @gmp_init($subid, 10);
                if (false === $n) {
                    throw new \UnexpectedValueException(
                        "'$subid' is not a number.");
                }
                $subids[] = $n;
            }
        }
        return $subids;
    }
    
    /**
     * Implode an array of sub IDs to dotted OID format.
     *
     * @param \GMP ...$subids
     * @return string
     */
    protected static function _implodeSubIDs(\GMP ...$subids): string
    {
        return implode(".",
            array_map(function ($num) {
                return gmp_strval($num, 10);
            }, $subids));
    }
    
    /**
     * Encode sub ID's to DER.
     *
     * @param \GMP ...$subids
     * @return string
     */
    protected static function _encodeSubIDs(\GMP ...$subids): string
    {
        $data = "";
        foreach ($subids as $subid) {
            // if number fits to one base 128 byte
            if ($subid < 128) {
                $data .= chr(intval($subid));
            } else { // encode to multiple bytes
                $bytes = [];
                do {
                    array_unshift($bytes, 0x7f & gmp_intval($subid));
                    $subid >>= 7;
                } while ($subid > 0);
                // all bytes except last must have bit 8 set to one
                foreach (array_splice($bytes, 0, -1) as $byte) {
                    $data .= chr(0x80 | $byte);
                }
                $data .= chr(reset($bytes));
            }
        }
        return $data;
    }
    
    /**
     * Decode sub ID's from DER data.
     *
     * @param string $data
     * @throws DecodeException
     * @return \GMP[] Array of GMP numbers
     */
    protected static function _decodeSubIDs(string $data): array
    {
        $subids = [];
        $idx = 0;
        $end = strlen($data);
        while ($idx < $end) {
            $num = gmp_init("0", 10);
            while (true) {
                if ($idx >= $end) {
                    throw new DecodeException("Unexpected end of data.");
                }
                $byte = ord($data[$idx++]);
                $num |= $byte & 0x7f;
                // bit 8 of the last octet is zero
                if (!($byte & 0x80)) {
                    break;
                }
                $num <<= 7;
            }
            $subids[] = $num;
        }
        return $subids;
    }
}
