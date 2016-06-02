<?php

namespace ASN1\Type\Tagged;

use ASN1\Component\Identifier;
use ASN1\Element;
use ASN1\ElementWrapper;
use ASN1\Feature\ElementBase;


interface ImplicitTagging extends ElementBase
{
	/**
	 * Get implicitly tagged wrapped element.
	 *
	 * @param int $tag Tag of the element
	 * @param int $class Expected type class of the element
	 * @throws \UnexpectedValueException If expectation fails
	 * @return ElementWrapper
	 */
	public function implicit($tag, $class = Identifier::CLASS_UNIVERSAL);
}
