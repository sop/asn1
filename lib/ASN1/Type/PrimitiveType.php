<?php

namespace ASN1\Type;

/**
 * Trait for primitive types.
 */
trait PrimitiveType
{
    /**
     *
     * @see \ASN1\Element::isConstructed()
     * @return boolean
     */
    public function isConstructed()
    {
        return false;
    }
}
