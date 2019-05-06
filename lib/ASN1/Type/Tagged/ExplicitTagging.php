<?php

declare(strict_types = 1);

namespace Sop\ASN1\Type\Tagged;

use Sop\ASN1\Feature\ElementBase;
use Sop\ASN1\Type\UnspecifiedType;

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
     * @todo Remove expectation checking
     *
     * @param null|int $expectedTag Expected tag of the underlying type
     *
     * @throws \UnexpectedValueException If expectation fails
     *
     * @return \Sop\ASN1\Type\UnspecifiedType
     */
    public function explicit(?int $expectedTag = null): UnspecifiedType;
}
