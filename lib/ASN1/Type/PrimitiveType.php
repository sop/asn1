<?php

namespace ASN1\Type;


/**
 * Trait for primitive types.
 */
trait PrimitiveType
{
	public function isConstructed() {
		return false;
	}
}
