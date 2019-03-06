<?php
declare(strict_types = 1);

namespace ASN1\Type\Primitive;

use ASN1\Type\PrimitiveString;
use ASN1\Type\UniversalClass;

/**
 * Implements <i>IA5String</i> type.
 */
class IA5String extends PrimitiveString
{
    use UniversalClass;
    
    /**
     * Constructor.
     *
     * @param string $string
     */
    public function __construct(string $string)
    {
        $this->_typeTag = self::TYPE_IA5_STRING;
        parent::__construct($string);
    }
    
    /**
     *
     * {@inheritdoc}
     */
    protected function _validateString(string $string): bool
    {
        return preg_match('/[^\x01-\x7f]/', $string) == 0;
    }
}
