<?php

namespace ASN1\Type\Tagged;

use ASN1\Element;
use ASN1\ElementWrapper;


/**
 * Implements explicit tagging mode.
 *
 * Explicit tagging wraps a type by prepending a tag. Underlying DER encoding
 * is not changed.
 */
class ExplicitlyTaggedType extends ContextSpecificTaggedType implements 
	ExplicitTagging
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
		return true;
	}
	
	protected function _encodedContentDER() {
		return $this->_element->toDER();
	}
	
	/**
	 *
	 * @see \ASN1\Type\Tagged\ExplicitTagging::explicit()
	 * @return ElementWrapper
	 */
	public function explicit($expectedTag = null) {
		if (isset($expectedTag)) {
			$this->_element->expectType($expectedTag);
		}
		return new ElementWrapper($this->_element);
	}
}
