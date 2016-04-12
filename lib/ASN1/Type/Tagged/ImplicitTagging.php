<?php

namespace ASN1\Type\Tagged;

use ASN1\Element;
use ASN1\Component\Identifier;


interface ImplicitTagging
{
	/**
	 * Get wrapped element.
	 *
	 * @param int $tag Tag of the element
	 * @param int $class Type class of the element
	 * @return Element
	 */
	public function implicit($tag, $class = Identifier::CLASS_UNIVERSAL);
}
