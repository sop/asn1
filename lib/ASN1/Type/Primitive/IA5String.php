<?php

namespace ASN1\Type\Primitive;

use ASN1\Type\UniversalClass;
use ASN1\Type\PrimitiveString;


class IA5String extends PrimitiveString
{
	use UniversalClass;
	
	/**
	 * Constructor
	 * 
	 * @param string $string
	 */
	public function __construct($string) {
		parent::__construct($string);
		$this->_typeTag = self::TYPE_IA5_STRING;
	}
	
	protected function _validateString($string) {
		return preg_match('/[^\x01-\x7f]/', $string) === 0;
	}
}
