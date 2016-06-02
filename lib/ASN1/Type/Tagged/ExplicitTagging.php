<?php

namespace ASN1\Type\Tagged;

use ASN1\Element;
use ASN1\Feature\ElementBase;
use ASN1\Type\UnspecifiedType;


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
	 * @return UnspecifiedType
	 */
	public function explicit($expectedTag = null);
}
