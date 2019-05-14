<?php

declare(strict_types = 1);

namespace Sop\ASN1\Feature;

/**
 * Interface for classes that may be cast to string.
 */
interface Stringable
{
    /**
     * Convert object to string.
     *
     * @return string
     */
    public function __toString(): string;

    /**
     * Get the string representation of the type.
     *
     * @return string
     */
    public function string(): string;
}
