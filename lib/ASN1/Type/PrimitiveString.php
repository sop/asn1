<?php

namespace ASN1\Type;

use ASN1\Component\Identifier;
use ASN1\Component\Length;
use ASN1\Exception\DecodeException;


/**
 * Base class for primitive strings.
 */
abstract class PrimitiveString extends StringType
{
	use PrimitiveType;
	
	protected function _encodedContentDER() {
		return $this->_string;
	}
	
	protected static function _decodeFromDER(Identifier $identifier, $data, 
			&$offset) {
		$idx = $offset;
		if (!$identifier->isPrimitive()) {
			throw new DecodeException("DER encoded string must be primitive.");
		}
		$length = Length::expectFromDER($data, $idx);
		$str = $length->length() ? substr($data, $idx, $length->length()) : "";
		if (false === $str) {
			// This branch should never occur, because length is checked.
			// @codeCoverageIgnoreStart
			throw new \LengthException(
				"Failed to extract substring, stringlen=" . strlen($data) .
					 ", start=$idx, length=" . $length->length() . ".");
			// @codeCoverageIgnoreEnd
		}
		$offset = $idx + $length->length();
		try {
			return new static($str);
		} catch (\InvalidArgumentException $e) {
			throw new DecodeException($e->getMessage(), null, $e);
		}
	}
}
