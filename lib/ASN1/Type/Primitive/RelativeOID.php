<?php
declare(strict_types = 1);

namespace ASN1\Type\Primitive;

use ASN1\Component\Identifier;
use ASN1\Component\Length;
use ASN1\Feature\ElementBase;

/**
 * Implements <i>RELATIVE-OID</i> type.
 */
class RelativeOID extends ObjectIdentifier
{
    /**
     * Constructor.
     *
     * @param string $oid OID in dotted format
     */
    public function __construct(string $oid)
    {
        $this->_oid = $oid;
        $this->_subids = self::_explodeDottedOID($oid);
        $this->_typeTag = self::TYPE_RELATIVE_OID;
    }
    
    /**
     *
     * {@inheritdoc}
     */
    protected function _encodedContentDER(): string
    {
        return self::_encodeSubIDs(...$this->_subids);
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
        $offset = $idx + $len;
        return new self(self::_implodeSubIDs(...$subids));
    }
}
