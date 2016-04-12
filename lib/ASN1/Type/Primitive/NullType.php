<?php

namespace ASN1\Type\Primitive;

use ASN1\Element;
use ASN1\Type\PrimitiveType;
use ASN1\Type\UniversalClass;
use ASN1\Component\Length;
use ASN1\Component\Identifier;
use ASN1\Exception\DecodeException;


class NullType extends Element
{
	use UniversalClass;
	use PrimitiveType;
	
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->_typeTag = self::TYPE_NULL;
	}
	
	protected function _encodedContentDER() {
		return "";
	}
	
	protected static function _decodeFromDER(
			Identifier $identifier, $data, &$offset) {
		$idx = $offset;
		if (!$identifier->isPrimitive()) {
			throw new DecodeException("Null value must be primitive");
		}
		// null type has always zero length
		Length::expectFromDER($data, $idx, 0);
		$offset = $idx;
		return new self();
	}
}
