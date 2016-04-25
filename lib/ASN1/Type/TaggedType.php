<?php

namespace ASN1\Type;

use ASN1\Component\Identifier;
use ASN1\Component\Length;
use ASN1\Element;
use ASN1\Type\Tagged\DERTaggedType;


/**
 * Base class for context-specific types.
 */
abstract class TaggedType extends Element
{
	protected static function _decodeFromDER(Identifier $identifier, $data, 
			&$offset) {
		$idx = $offset;
		$type = new DERTaggedType($identifier, $data, $idx);
		$length = Length::expectFromDER($data, $idx);
		$offset = $idx + $length->length();
		return $type;
	}
}
