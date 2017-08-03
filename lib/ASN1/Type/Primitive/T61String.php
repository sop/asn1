<?php

namespace ASN1\Type\Primitive;

use ASN1\Type\PrimitiveString;
use ASN1\Type\UniversalClass;

/**
 * Implements <i>T61String</i> type.
 */
class T61String extends PrimitiveString
{
    use UniversalClass;
    
    /**
     * Constructor.
     *
     * @param string $string
     */
    public function __construct($string)
    {
        $this->_typeTag = self::TYPE_T61_STRING;
        parent::__construct($string);
    }
    
    /**
     *
     * {@inheritdoc}
     */
    protected function _validateString($string)
    {
        // allow everything since there's literally
        // thousands of allowed characters (16 bit composed characters)
        return true;
    }
}
