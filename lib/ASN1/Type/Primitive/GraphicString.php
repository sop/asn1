<?php
declare(strict_types = 1);

namespace ASN1\Type\Primitive;

use ASN1\Type\PrimitiveString;
use ASN1\Type\UniversalClass;

/**
 * Implements <i>GraphicString</i> type.
 */
class GraphicString extends PrimitiveString
{
    use UniversalClass;
    
    /**
     * Constructor.
     *
     * @param string $string
     */
    public function __construct(string $string)
    {
        $this->_typeTag = self::TYPE_GRAPHIC_STRING;
        parent::__construct($string);
    }
    
    /**
     *
     * {@inheritdoc}
     */
    protected function _validateString(string $string): bool
    {
        // allow everything
        return true;
    }
}
