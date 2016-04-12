<?php

namespace ASN1\Type;

use ASN1\Component\Identifier;


/**
 * Trait for types of universal class
 */
trait UniversalClass
{
	public function typeClass() {
		return Identifier::CLASS_UNIVERSAL;
	}
}
