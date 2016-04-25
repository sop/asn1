<?php

namespace ASN1\Type\Tagged;

use ASN1\Element;


interface ExplicitTagging
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
