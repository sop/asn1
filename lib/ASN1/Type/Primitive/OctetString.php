<?php

namespace ASN1\Type\Primitive;

use ASN1\Type\PrimitiveString;
use ASN1\Type\UniversalClass;

/**
 * Implements <i>OCTET STRING</i> type.
 */
class OctetString extends PrimitiveString
{
    use UniversalClass;
    
    /**
     * Constructor.
     *
     * @param string $string
     */
    public function __construct($string)
    {
        $this->_typeTag = self::TYPE_OCTET_STRING;
        parent::__construct($string);
    }
}
