<?php

namespace ASN1\Type\Primitive;

use ASN1\Type\PrimitiveString;
use ASN1\Type\UniversalClass;


/**
 * Implements <i>VisibleString</i> type.
 */
class VisibleString extends PrimitiveString
{
	use UniversalClass;
	
	/**
	 * Constructor
	 *
	 * @param string $string
	 */
	public function __construct($string) {
		$this->_typeTag = self::TYPE_VISIBLE_STRING;
		parent::__construct($string);
	}
	
	protected function _validateString($string) {
		return preg_match('/[^\x20-\x7e]/', $string) == 0;
	}
}
