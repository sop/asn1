<?php

namespace ASN1\Type\Tagged;

use ASN1\Element;
use ASN1\Feature\ElementBase;


interface ExplicitTagging extends ElementBase
{
	/**
	 * Get explicitly tagged wrapped element.
	 *
	 * @param int|null $expectedTag Expected tag of the underlying type
	 * @throws \UnexpectedValueException If expectation fails
	 * @return Element
	 */
	public function explicit($expectedTag = null);
}
