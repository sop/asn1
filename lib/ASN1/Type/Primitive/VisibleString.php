<?php

namespace ASN1\Type\Primitive;

use ASN1\Type\UniversalClass;
use ASN1\Type\PrimitiveString;


class VisibleString extends PrimitiveString
{
	use UniversalClass;
	
	/**
	 * Constructor
	 * 
	 * @param string $string
	 */
	public function __construct($string) {
		parent::__construct($string);
		$this->_typeTag = self::TYPE_VISIBLE_STRING;
	}
	
	protected function _validateString($string) {
		return preg_match('/[^\x20-\x7e]/', $string) === 0;
	}
}
