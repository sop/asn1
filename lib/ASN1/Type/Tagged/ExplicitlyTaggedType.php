<?php

namespace ASN1\Type\Tagged;

use ASN1\Element;


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
	
	public function explicit($expectedTag = null) {
		if (isset($expectedTag)) {
			$this->_element->expectType($expectedTag);
		}
		return $this->_element;
	}
}
