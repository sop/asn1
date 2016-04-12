<?php

namespace ASN1\Type\Tagged;

use ASN1\Element;
use ASN1\Type\TaggedType;
use ASN1\Component\Identifier;


abstract class ContextSpecificTaggedType extends TaggedType
{
	/**
	 * Wrapped element
	 * 
	 * @var Element $_element
	 */
	protected $_element;
	
	/**
	 * Get wrapped element
	 * 
	 * @return Element
	 */
	public function element() {
		return $this->_element;
	}
	
	public function typeClass() {
		return Identifier::CLASS_CONTEXT_SPECIFIC;
	}
}
