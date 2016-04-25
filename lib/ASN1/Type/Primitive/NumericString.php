<?php

namespace ASN1\Type\Primitive;

use ASN1\Type\PrimitiveString;
use ASN1\Type\UniversalClass;


/**
 * Implements <i>NumericString</i> type.
 */
class NumericString extends PrimitiveString
{
	use UniversalClass;
	
	/**
	 * Constructor
	 *
	 * @param string $string
	 */
	public function __construct($string) {
		$this->_typeTag = self::TYPE_NUMERIC_STRING;
		parent::__construct($string);
	}
	
	protected function _validateString($string) {
		return preg_match('/[^0-9 ]/', $string) === 0;
	}
}
