<?php
declare(strict_types = 1);

namespace ASN1\Type\Primitive;

use ASN1\Type\PrimitiveString;
use ASN1\Type\UniversalClass;

/**
 * Implements <i>NumericString</i> type.
 */
class NumericString extends PrimitiveString
{
    use UniversalClass;
    
    /**
     * Constructor.
     *
     * @param string $string
     */
    public function __construct(string $string)
    {
        $this->_typeTag = self::TYPE_NUMERIC_STRING;
        parent::__construct($string);
    }
    
    /**
     *
     * {@inheritdoc}
     */
    protected function _validateString(string $string): bool
    {
        return preg_match('/[^0-9 ]/', $string) == 0;
    }
}
