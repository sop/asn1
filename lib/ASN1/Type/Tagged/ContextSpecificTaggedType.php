<?php

namespace ASN1\Type\Tagged;

use ASN1\Component\Identifier;
use ASN1\Element;
use ASN1\Type\TaggedType;


/**
 * Base class for context-specific elements.
 */
abstract class ContextSpecificTaggedType extends TaggedType
{
	/**
	 * Wrapped element.
	 *
	 * @var Element $_element
	 */
	protected $_element;
	
	/**
	 * Get the wrapped element.
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
