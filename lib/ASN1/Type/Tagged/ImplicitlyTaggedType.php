<?php

namespace ASN1\Type\Tagged;

use ASN1\Component\Identifier;
use ASN1\Element;
use ASN1\ElementWrapper;


/**
 * Implements implicit tagging mode.
 *
 * Implicit tagging changes the tag of the tagged type. This changes the
 * DER encoding of the type, and hence the abstract syntax must be known when
 * decoding the data.
 */
class ImplicitlyTaggedType extends ContextSpecificTaggedType implements 
	ImplicitTagging
{
	/**
	 * Constructor
	 *
	 * @param int $tag
	 * @param Element $element
	 */
	public function __construct($tag, Element $element) {
		$this->_typeTag = $tag;
		$this->_element = $element;
	}
	
	public function isConstructed() {
		// depends on the underlying type
		return $this->_element->isConstructed();
	}
	
	protected function _encodedContentDER() {
		return $this->_element->_encodedContentDER();
	}
	
	/**
	 *
	 * @see \ASN1\Type\Tagged\ImplicitTagging::implicit()
	 * @return ElementWrapper
	 */
	public function implicit($tag, $class = Identifier::CLASS_UNIVERSAL) {
		$this->_element->expectType($tag);
		if ($this->_element->typeClass() != $class) {
			throw new \UnexpectedValueException(
				"Type class " . Identifier::classToName($class) .
					 " expected, got " .
					 Identifier::classToName($this->_element->typeClass()) . ".");
		}
		return new ElementWrapper($this->_element);
	}
}
