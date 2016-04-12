<?php

namespace ASN1\Type\Primitive;

use ASN1\Type\UniversalClass;
use ASN1\Type\PrimitiveString;


class T61String extends PrimitiveString
{
	use UniversalClass;
	
	/**
	 * Constructor
	 * 
	 * @param string $string
	 */
	public function __construct($string) {
		parent::__construct($string);
		$this->_typeTag = self::TYPE_T61_STRING;
	}
	
	protected function _validateString($string) {
		// allow everything since there's literally
		// thousands of allowed characters (16 bit composed characters)
		return true;
	}
}
