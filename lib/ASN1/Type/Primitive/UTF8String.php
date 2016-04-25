<?php

namespace ASN1\Type\Primitive;

use ASN1\Type\PrimitiveString;
use ASN1\Type\UniversalClass;


/**
 * Implements <i>UTF8String</i> type.
 *
 * UTF8String is an Unicode string with UTF-8 encoding.
 */
class UTF8String extends PrimitiveString
{
	use UniversalClass;
	
	/**
	 * Constructor
	 *
	 * @param string $string
	 */
	public function __construct($string) {
		$this->_typeTag = self::TYPE_UTF8_STRING;
		parent::__construct($string);
	}
	
	protected function _validateString($string) {
		return mb_check_encoding($string, "UTF-8");
	}
}
