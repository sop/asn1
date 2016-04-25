<?php

namespace ASN1\Type\Primitive;

use ASN1\Type\PrimitiveString;
use ASN1\Type\UniversalClass;


/**
 * Implements <i>IA5String</i> type.
 */
class IA5String extends PrimitiveString
{
	use UniversalClass;
	
	/**
	 * Constructor
	 *
	 * @param string $string
	 */
	public function __construct($string) {
		$this->_typeTag = self::TYPE_IA5_STRING;
		parent::__construct($string);
	}
	
	protected function _validateString($string) {
		return preg_match('/[^\x01-\x7f]/', $string) === 0;
	}
}
