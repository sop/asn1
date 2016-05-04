<?php

namespace ASN1;

use ASN1\Component\Identifier;
use ASN1\Component\Length;


/**
 * Container for raw DER encoded data.
 * May be inserted into structure without decoding first.
 */
class DERData extends Element
{
	/**
	 * DER encoded data.
	 *
	 * @var string $_der
	 */
	protected $_der;
	
	/**
	 * Identifier of the underlying type.
	 *
	 * @var Identifier $_identifier
	 */
	protected $_identifier;
	
	/**
	 * Offset to the content in DER data.
	 *
	 * @var int $_contentOffset
	 */
	protected $_contentOffset = 0;
	
	/**
	 * Constructor
	 *
	 * @param string $data DER encoded data
	 */
	public function __construct($data) {
		$this->_identifier = Identifier::fromDER($data, $this->_contentOffset);
		Length::expectFromDER($data, $this->_contentOffset);
		$this->_der = $data;
		$this->_typeTag = $this->_identifier->tag();
	}
	
	public function typeClass() {
		return $this->_identifier->typeClass();
	}
	
	public function isConstructed() {
		return $this->_identifier->isConstructed();
	}
	
	protected function _encodedContentDER() {
		return substr($this->_der, $this->_contentOffset);
	}
	
	protected static function _decodeFromDER(Identifier $identifier, $data, 
			&$offset) {
		throw new \LogicException("Not implemented.");
	}
	
	public function toDER() {
		return $this->_der;
	}
}
