<?php

namespace ASN1\Type\Primitive;

use ASN1\Type\UniversalClass;
use ASN1\Type\PrimitiveString;


class VideotexString extends PrimitiveString
{
	use UniversalClass;
	
	/**
	 * Constructor
	 * 
	 * @param string $string
	 */
	public function __construct($string) {
		parent::__construct($string);
		$this->_typeTag = self::TYPE_VIDEOTEX_STRING;
	}
	
	protected function _validateString($string) {
		// allow everything
		return true;
	}
}
