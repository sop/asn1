<?php

namespace ASN1\Type\Tagged;

use ASN1\Element;
use ASN1\Component\Identifier;


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
		// depends on underlying type
		return $this->_element->isConstructed();
	}
	
	protected function _encodedContentDER() {
		return $this->_element->_encodedContentDER();
	}
	
	public function implicit($tag, $class = Identifier::CLASS_UNIVERSAL) {
		$this->_element->expectType($tag);
		if ($this->_element->typeClass() !== $class) {
			throw new \UnexpectedValueException(
				"Type class " . Identifier::classToName($class) .
					 " expected, got " .
					 Identifier::classToName($this->_element->typeClass()));
		}
		return $this->_element;
	}
}
