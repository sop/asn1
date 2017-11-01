<?php

declare(strict_types=1);

namespace ASN1\Component;

use ASN1\Exception\DecodeException;
use ASN1\Feature\Encodable;

/**
 * Class to represent BER/DER length octets.
 */
class Length implements Encodable
{
    /**
     * Length.
     *
     * @var int|string
     */
    private $_length;
    
    /**
     * Whether length is indefinite.
     *
     * @var boolean
     */
    private $_indefinite;
    
    /**
     * Constructor.
     *
     * @param int|string $length Length
     * @param boolean $indefinite Whether length is indefinite
     */
    public function __construct($length, bool $indefinite = false)
    {
        $this->_length = $length;
        $this->_indefinite = $indefinite;
    }
    
    /**
     * Decode length component from DER data.
     *
     * @param string $data DER encoded data
     * @param int|null $offset Reference to the variable that contains offset
     *        into the data where to start parsing. Variable is updated to
     *        the offset next to the parsed length component. If null, start
     *        from offset 0.
     * @throws DecodeException If decoding fails
     * @return self
     */
    public static function fromDER(string $data, int &$offset = null): self
    {
        $idx = $offset ? $offset : 0;
        $datalen = strlen($data);
        if ($idx >= $datalen) {
            throw new DecodeException("Invalid offset.");
        }
        $indefinite = false;
        $byte = ord($data[$idx++]);
        // bits 7 to 1
        $length = (0x7f & $byte);
        // long form
        if (0x80 & $byte) {
            if (!$length) {
                $indefinite = true;
            } else {
                if ($idx + $length > $datalen) {
                    throw new DecodeException("Too many length octets.");
                }
                $length = self::_decodeLongFormLength($length, $data, $idx);
            }
        }
        if (isset($offset)) {
            $offset = $idx;
        }
        return new self($length, $indefinite);
    }
    
    /**
     * Decode long form length.
     *
     * @param int $length Number of octets
     * @param string $data Data
     * @param int $offset Reference to the variable containing offset to the
     *        data.
     * @throws DecodeException If decoding fails
     * @return int|string
     */
    private static function _decodeLongFormLength(int $length, string $data, int &$offset)
    {
        // first octet must not be 0xff (spec 8.1.3.5c)
        if ($length == 127) {
            throw new DecodeException("Invalid number of length octets.");
        }
        $num = gmp_init(0, 10);
        while (--$length >= 0) {
            $byte = ord($data[$offset++]);
            $num <<= 8;
            $num |= $byte;
        }

        return gmp_strval($num);
    }
    
    /**
     * Decode length from DER.
     *
     * Throws an exception if length doesn't match with expected or if data
     * doesn't contain enough bytes.
     *
     * @see self::fromDER
     * @param string $data DER data
     * @param int $offset Reference to the offset variable
     * @param int|null $expected Expected length, null to bypass checking
     * @throws DecodeException If decoding or expectation fails
     * @return self
     */
    public static function expectFromDER(string $data, int &$offset, int $expected = null): self
    {
        $idx = $offset;
        $length = self::fromDER($data, $idx);
        // DER encoding must have definite length (spec 10.1)
        if ($length->isIndefinite()) {
            throw new DecodeException("DER encoding must have definite length.");
        }
        // if certain length was expected
        if (isset($expected) && $expected != $length->_length) {
            throw new DecodeException(
                sprintf("Expected length %d, got %d.", $expected,
                    $length->_length));
        }
        // check that enough data is available
        if (strlen($data) < $idx + $length->_length) {
            throw new DecodeException(
                sprintf("Length %d overflows data, %d bytes left.",
                    $length->_length, strlen($data) - $idx));
        }
        $offset = $idx;
        return $length;
    }
    
    /**
     *
     * @see Encodable::toDER()
     * @throws \DomainException If length is too large to encode
     * @return string
     */
    public function toDER(): string
    {
        $bytes = [];
        if ($this->_indefinite) {
            $bytes[] = 0x80;
        } else {
            $num = gmp_init($this->_length, 10);
            // long form
            if ($num > 127) {
                $octets = [];
                for (; $num > 0; $num >>= 8) {
                    $octets[] = gmp_intval(0xff & $num);
                }
                $count = count($octets);
                // first octet must not be 0xff
                if ($count >= 127) {
                    throw new \DomainException("Too many length octets.");
                }
                $bytes[] = 0x80 | $count;
                foreach (array_reverse($octets) as $octet) {
                    $bytes[] = $octet;
                }
            } else { // short form
                $bytes[] = gmp_intval($num);
            }
        }
        return pack("C*", ...$bytes);
    }
    
    /**
     * Get the length.
     *
     * @throws \LogicException If length is indefinite
     * @return int|string
     */
    public function length()
    {
        if ($this->_indefinite) {
            throw new \LogicException("Length is indefinite.");
        }
        return $this->_length;
    }
    
    /**
     * Whether length is indefinite.
     *
     * @return boolean
     */
    public function isIndefinite(): bool
    {
        return $this->_indefinite;
    }
}
