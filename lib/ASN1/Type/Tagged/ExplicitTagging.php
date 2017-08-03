<?php

namespace ASN1\Type\Tagged;

use ASN1\Feature\ElementBase;

/**
 * Interface for classes providing explicit tagging.
 */
interface ExplicitTagging extends ElementBase
{
    /**
     * Get explicitly tagged wrapped element.
     *
     * NOTE! Expectation checking is deprecated and shall be done
     * with UnspecifiedType.
     *
     * @param int|null $expectedTag Expected tag of the underlying type
     * @throws \UnexpectedValueException If expectation fails
     * @return \ASN1\Type\UnspecifiedType
     */
    public function explicit($expectedTag = null);
}
