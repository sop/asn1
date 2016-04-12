<?php

namespace ASN1\Type\Primitive;

use ASN1\Type\UniversalClass;
use ASN1\Type\PrimitiveString;


class UniversalString extends PrimitiveString
{
	use UniversalClass;
	
	/**
	 * Constructor
	 * 
	 * @param string $string
	 */
	public function __construct($string) {
		parent::__construct($string);
		$this->_typeTag = self::TYPE_UNIVERSAL_STRING;
	}
	
	protected function _validateString($string) {
		// UCS-4 has fixed with of 4 octets (32 bits)
		if (strlen($string) % 4 !== 0) {
			return false;
		}
		return true;
	}
}
