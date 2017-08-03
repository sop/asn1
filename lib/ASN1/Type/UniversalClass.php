<?php

namespace ASN1\Type;

use ASN1\Component\Identifier;

/**
 * Trait for types of universal class.
 */
trait UniversalClass
{
    /**
     *
     * @see \ASN1\Element::typeClass()
     * @return int
     */
    public function typeClass()
    {
        return Identifier::CLASS_UNIVERSAL;
    }
}
