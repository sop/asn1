<?php
declare(strict_types = 1);

namespace ASN1\Feature;

/**
 * Interface for classes that may be cast to string.
 */
interface Stringable
{
    /**
     * Get the string representation of the type.
     *
     * @return string
     */
    public function string(): string;
    
    /**
     *
     * @inheritdoc
     * @return string
     */
    public function __toString(): string;
}
