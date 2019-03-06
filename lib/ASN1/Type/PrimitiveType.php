<?php
declare(strict_types = 1);

namespace ASN1\Type;

/**
 * Trait for primitive types.
 */
trait PrimitiveType
{
    /**
     *
     * @see \ASN1\Element::isConstructed()
     * @return bool
     */
    public function isConstructed(): bool
    {
        return false;
    }
}
