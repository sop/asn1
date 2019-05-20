<?php

declare(strict_types = 1);

namespace Sop\ASN1\Type;

/**
 * Interface to mark types that encode a time as a string.
 */
interface TimeType extends StringType
{
    /**
     * Get the date and time.
     *
     * @return \DateTimeImmutable
     */
    public function dateTime(): \DateTimeImmutable;
}
