<?php

namespace ASN1\Type\Primitive;

use ASN1\Type\UniversalClass;
use ASN1\Type\PrimitiveString;


class BMPString extends PrimitiveString
{
	use UniversalClass;
	
	/**
	 * Constructor
	 * 
	 * @param string $string
	 */
	public function __construct($string) {
		parent::__construct($string);
		$this->_typeTag = self::TYPE_BMP_STRING;
	}
	
	protected function _validateString($string) {
		// UCS-2 has fixed with of 2 octets (16 bits)
		if (strlen($string) % 2 !== 0) {
			return false;
		}
		return true;
	}
}
