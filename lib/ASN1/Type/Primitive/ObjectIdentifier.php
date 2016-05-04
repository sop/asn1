<?php

namespace ASN1\Type\Primitive;

use ASN1\Component\Identifier;
use ASN1\Component\Length;
use ASN1\Element;
use ASN1\Exception\DecodeException;
use ASN1\Type\PrimitiveType;
use ASN1\Type\UniversalClass;


/**
 * Implements <i>OBJECT IDENTIFIER</i> type.
 */
class ObjectIdentifier extends Element
{
	use UniversalClass;
	use PrimitiveType;
	
	/**
	 * Object identifier in dotted format.
	 *
	 * @var string
	 */
	private $_oid;
	
	/**
	 * Constructor
	 *
	 * @param string $oid OID in dotted format
	 */
	public function __construct($oid) {
		assert('is_string($oid)', "got " . gettype($oid));
		$this->_oid = $oid;
		$this->_typeTag = self::TYPE_OBJECT_IDENTIFIER;
	}
	
	/**
	 * Get OID in dotted format.
	 *
	 * @return string
	 */
	public function oid() {
		return $this->_oid;
	}
	
	protected function _encodedContentDER() {
		$subids = array();
		// convert oid to array of subid's as gmp integers
		foreach (explode(".", $this->_oid) as $subid) {
			$subids[] = gmp_init($subid, 10);
		}
		// encode first two subids to one according to spec section 8.19.4
		if (count($subids) >= 2) {
			$num = ($subids[0] * 40) + $subids[1];
			array_splice($subids, 0, 2, array($num));
		}
		$data = "";
		foreach ($subids as $subid) {
			// if number fits to one base 128 byte
			if ($subid < 128) {
				$data .= chr(intval($subid));
			} else { // encode to multiple bytes
				$bytes = array();
				do {
					array_unshift($bytes, 0x7f & gmp_intval($subid));
					$subid >>= 7;
				} while ($subid > 0);
				// all bytes except last must have bit 8 set to one
				foreach (array_splice($bytes, 0, -1) as $byte) {
					$data .= chr(0x80 | $byte);
				}
				$data .= chr(reset($bytes));
			}
		}
		return $data;
	}
	
	protected static function _decodeFromDER(Identifier $identifier, $data, 
			&$offset) {
		$idx = $offset;
		$length = Length::expectFromDER($data, $idx);
		$end = $idx + $length->length();
		$subids = array();
		while ($idx < $end) {
			$num = gmp_init("0", 10);
			while (true) {
				if ($idx >= $end) {
					throw new DecodeException("Unexpected end of data.");
				}
				$byte = ord($data[$idx++]);
				$num |= $byte & 0x7f;
				// bit 8 of the last octet is zero
				if (!($byte & 0x80)) {
					break;
				}
				$num <<= 7;
			}
			$subids[] = $num;
		}
		// decode first subidentifier according to spec section 8.19.4
		if (isset($subids[0])) {
			list($x, $y) = gmp_div_qr($subids[0], "40");
			array_splice($subids, 0, 1, array($x, $y));
		}
		$offset = $idx;
		// convert numbers to strings
		$subids = array_map(
			function ($num) {
				return gmp_strval($num, 10);
			}, $subids);
		return new self(implode(".", $subids));
	}
}
