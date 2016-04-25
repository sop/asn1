<?php

namespace ASN1\Type\Tagged;

use ASN1\Component\Identifier;
use ASN1\Element;


interface ImplicitTagging
{
	/**
	 * Get implicitly tagged wrapped element.
	 *
	 * @param int $tag Tag of the element
	 * @param int $class Type class of the element
	 * @return Element
	 */
	public function implicit($tag, $class = Identifier::CLASS_UNIVERSAL);
}
