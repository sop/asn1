<?php

namespace ASN1\Type\Primitive;


/**
 * Implements <i>ENUMERATED</i> type.
 */
class Enumerated extends Integer
{
	/**
	 * Constructor
	 *
	 * @param int|string $number
	 */
	public function __construct($number) {
		$this->_typeTag = self::TYPE_ENUMERATED;
		parent::__construct($number);
	}
}
