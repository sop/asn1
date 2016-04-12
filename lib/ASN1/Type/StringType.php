<?php

namespace ASN1\Type;

use ASN1\Element;


abstract class StringType extends Element
{
	/**
	 * Value
	 * 
	 * @var string $_string
	 */
	protected $_string;
	
	/**
	 * Constructor
	 * 
	 * @param string $string
	 * @throws \InvalidArgumentException
	 */
	public function __construct($string) {
		assert('is_string($string)', "got " . gettype($string));
		if (!$this->_validateString($string)) {
			throw new \InvalidArgumentException(
				"String is not valid in this context");
		}
		$this->_string = $string;
	}
	
	/**
	 * Get string value
	 * 
	 * @return string
	 */
	public function str() {
		return $this->_string;
	}
	
	/**
	 * Check whether string is valid for the implemented type.
	 * Overridden in concrete classes.
	 * 
	 * @param string $string
	 * @return bool
	 */
	protected function _validateString($string) {
		return true;
	}
}
