<?php

namespace ASN1\Type\Primitive;

use ASN1\Type\UniversalClass;
use ASN1\Type\PrimitiveString;


class PrintableString extends PrimitiveString
{
	use UniversalClass;
	
	/**
	 * Constructor
	 * 
	 * @param string $string
	 */
	public function __construct($string) {
		parent::__construct($string);
		$this->_typeTag = self::TYPE_PRINTABLE_STRING;
	}
	
	protected function _validateString($string) {
		$chars = preg_quote(" '()+,-./:=?]", "/");
		return preg_match('/[^A-Za-z0-9' . $chars . ']/', $string) === 0;
	}
}
