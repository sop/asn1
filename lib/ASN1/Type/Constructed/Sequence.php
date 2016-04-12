<?php

namespace ASN1\Type\Constructed;

use ASN1\Element;
use ASN1\Type\Structure;


class Sequence extends Structure
{
	/**
	 * Constructor
	 * 
	 * @param Element ...$elements
	 */
	public function __construct(Element ...$elements) {
		parent::__construct(...$elements);
		$this->_typeTag = self::TYPE_SEQUENCE;
	}
}
