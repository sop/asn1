<?php

namespace ASN1\Type\Primitive;

use ASN1\Component\Identifier;
use ASN1\Component\Length;


/**
 * Implements <i>RELATIVE-OID</i> type.
 */
class RelativeOID extends ObjectIdentifier
{
	/**
	 * Constructor
	 *
	 * @param string $oid OID in dotted format
	 */
	public function __construct($oid) {
		assert('is_string($oid)', "got " . gettype($oid));
		$this->_oid = $oid;
		$this->_typeTag = self::TYPE_RELATIVE_OID;
	}
	
	protected function _encodedContentDER() {
		return self::_encodeSubIDs(...self::_explodeDottedOID($this->_oid));
	}
	
	protected static function _decodeFromDER(Identifier $identifier, $data, 
			&$offset) {
		$idx = $offset;
		$len = Length::expectFromDER($data, $idx)->length();
		$subids = self::_decodeSubIDs(substr($data, $idx, $len));
		$offset = $idx + $len;
		return new self(self::_implodeSubIDs(...$subids));
	}
}
