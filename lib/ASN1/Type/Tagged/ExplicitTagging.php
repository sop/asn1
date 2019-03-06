<?php
declare(strict_types = 1);

namespace ASN1\Type\Tagged;

use ASN1\Feature\ElementBase;
use ASN1\Type\UnspecifiedType;

/**
 * Interface for classes providing explicit tagging.
 */
interface ExplicitTagging extends ElementBase
{
    /**
     * Get explicitly tagged wrapped element.
     *
     * <strong>NOTE!</strong> Expectation checking is deprecated and shall be
     * done with <code>UnspecifiedType</code>.
     *
     * @param int|null $expectedTag Expected tag of the underlying type
     * @throws \UnexpectedValueException If expectation fails
     * @return \ASN1\Type\UnspecifiedType
     */
    public function explicit($expectedTag = null): UnspecifiedType;
}
