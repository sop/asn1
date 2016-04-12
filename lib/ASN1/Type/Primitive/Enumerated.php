<?php

namespace ASN1\Type\Primitive;


class Enumerated extends Integer
{
	/**
	 * Constructor
	 *
	 * @param int|string $number
	 */
	public function __construct($number) {
		parent::__construct($number);
		$this->_typeTag = self::TYPE_ENUMERATED;
	}
}
