<?php

namespace ASN1\Type\Tagged;

use ASN1\Component\Identifier;
use ASN1\Component\Length;
use ASN1\Element;
use ASN1\Type\TaggedType;


/**
 * Intermediate class to store tagged DER data.
 *
 * <code>implicit($tag)</code> or <code>explicit()</code> method is used
 * to decode the actual element, which is only known by the abstract syntax
 * of data structure.
 *
 * May be encoded back to complete DER encoding.
 */
class DERTaggedType extends TaggedType implements ExplicitTagging, 
	ImplicitTagging
{
	/**
	 * Identifier.
	 *
	 * @var Identifier
	 */
	private $_identifier;
	
	/**
	 * DER data.
	 *
	 * @var string
	 */
	private $_data;
	
	/**
	 * Offset to data.
	 *
	 * @var int
	 */
	private $_offset;
	
	/**
	 * Constructor
	 *
	 * @param Identifier $identifier
	 * @param string $data
	 * @param int $offset Offset to next byte after identifier
	 */
	public function __construct(Identifier $identifier, $data, $offset) {
		$this->_identifier = $identifier;
		$this->_data = $data;
		$this->_offset = $offset;
		$this->_typeTag = intval($identifier->tag());
	}
	
	public function typeClass() {
		return $this->_identifier->typeClass();
	}
	
	public function isConstructed() {
		return $this->_identifier->isConstructed();
	}
	
	protected function _encodedContentDER() {
		$idx = $this->_offset;
		$length = Length::expectFromDER($this->_data, $idx);
		return substr($this->_data, $idx, $length->length());
	}
	
	public function implicit($tag, $class = Identifier::CLASS_UNIVERSAL) {
		$identifier = $this->_identifier->withClass($class)->withTag($tag);
		$cls = self::_determineImplClass($identifier);
		$idx = $this->_offset;
		return $cls::_decodeFromDER($identifier, $this->_data, $idx);
	}
	
	public function explicit($expectedTag = null) {
		$idx = $this->_offset;
		Length::expectFromDER($this->_data, $idx);
		$element = Element::fromDER($this->_data, $idx);
		if (isset($expectedTag)) {
			$element->expectType($expectedTag);
		}
		return $element;
	}
}
