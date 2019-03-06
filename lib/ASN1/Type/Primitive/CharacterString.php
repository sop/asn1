<?php
declare(strict_types = 1);

namespace ASN1\Type\Primitive;

use ASN1\Type\PrimitiveString;
use ASN1\Type\UniversalClass;

class CharacterString extends PrimitiveString
{
    use UniversalClass;
    
    /**
     * Constructor.
     *
     * @param string $string
     */
    public function __construct(string $string)
    {
        $this->_typeTag = self::TYPE_CHARACTER_STRING;
        parent::__construct($string);
    }
}
