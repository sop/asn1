<?php

namespace ASN1\Type\Tagged;

use ASN1\Element;
use ASN1\ElementWrapper;
use ASN1\Feature\ElementBase;


interface ExplicitTagging extends ElementBase
{
	/**
	 * Get explicitly tagged wrapped element.
	 *
	 * NOTE! Expectation checking is deprecated and shall be done
	 * with ElementWrapper.
	 *
	 * @param int|null $expectedTag Expected tag of the underlying type
	 * @throws \UnexpectedValueException If expectation fails
	 * @return ElementWrapper
	 */
	public function explicit($expectedTag = null);
}
