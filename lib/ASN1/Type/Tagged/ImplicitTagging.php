<?php
declare(strict_types = 1);

namespace ASN1\Type\Tagged;

use ASN1\Component\Identifier;
use ASN1\Feature\ElementBase;
use ASN1\Type\UnspecifiedType;

/**
 * Interface for classes providing implicit tagging.
 */
interface ImplicitTagging extends ElementBase
{
    /**
     * Get implicitly tagged wrapped element.
     *
     * @param int $tag Tag of the element
     * @param int $class Expected type class of the element
     * @throws \UnexpectedValueException If expectation fails
     * @return \ASN1\Type\UnspecifiedType
     */
    public function implicit(int $tag, int $class = Identifier::CLASS_UNIVERSAL): UnspecifiedType;
}
