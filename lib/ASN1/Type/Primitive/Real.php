<?php

namespace ASN1\Type\Primitive;

use ASN1\Component\Identifier;
use ASN1\Component\Length;
use ASN1\Element;
use ASN1\Exception\DecodeException;
use ASN1\Type\PrimitiveType;
use ASN1\Type\UniversalClass;


/**
 * Implements <i>REAL</i> type.
 *
 * @todo Implement parsing. Currently just parks DER data for re-encoding.
 */
class Real extends Element
{
	use UniversalClass;
	use PrimitiveType;
	
	private $_number;
	
	private $_der;
	
	/**
	 * Constructor
	 *
	 * @param string $number
	 */
	public function __construct($number) {
		$this->_typeTag = self::TYPE_REAL;
		if (!self::_validateNumber($number)) {
			throw new \InvalidArgumentException("'$number' is not a valid real");
		}
		$this->_number = $number;
	}
	
	protected function _encodedContentDER() {
		if (!isset($this->_der)) {
			throw new \Exception("DER encoding of REAL value not implemented");
		}
		return $this->_der;
	}
	
	protected static function _decodeFromDER(Identifier $identifier, $data, 
			&$offset) {
		$idx = $offset;
		$length = Length::expectFromDER($data, $idx);
		// if length is zero, value is zero (spec 8.5.2)
		if (!$length->length()) {
			$obj = new self("0");
		} else {
			$bytes = substr($data, $idx, $length->length());
			$byte = ord($bytes[0]);
			if (0x80 & $byte) { // bit 8 = 1
				// binary encoding
				$obj = self::_decodeBinaryEncoding($bytes);
			} else if ($byte >> 6 == 0x00) { // bit 8 = 0, bit 7 = 0
				// decimal encoding
				$obj = self::_decodeDecimalEncoding($bytes);
			} else if ($byte >> 6 == 0x01) { // bit 8 = 0, bit 7 = 1
				// SpecialRealValue
				$obj = self::_decodeSpecialRealValue($bytes);
			}
		}
		$obj->_der = substr($data, $idx, $length->length());
		$offset = $idx + $length->length();
		return $obj;
	}
	
	protected static function _decodeBinaryEncoding($data) {
		throw new \Exception("Not implemented");
	}
	
	protected static function _decodeDecimalEncoding($data) {
		$nr = ord($data[0]) & 0x03;
		$str = substr($data, 1);
		return new self($str);
	}
	
	protected static function _decodeSpecialRealValue($data) {
		if (strlen($data) != 1) {
			throw new DecodeException(
				"SpecialRealValue must have one content octet");
		}
		$byte = ord($data[0]);
		if ($byte == 0x40) { // positive infinity
			return new self("+INF");
		} else if ($byte == 0x41) { // negative infinity
			return new self("-INF");
		} else {
			throw new DecodeException("Invalid SpecialRealValue encoding");
		}
	}
	
	/**
	 * Test that number is valid for this context
	 *
	 * @param mixed $num
	 * @return boolean
	 */
	private static function _validateNumber($num) {
		return true;
	}
}
