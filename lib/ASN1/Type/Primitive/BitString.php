<?php

namespace ASN1\Type\Primitive;

use ASN1\Component\Identifier;
use ASN1\Component\Length;
use ASN1\Exception\DecodeException;
use ASN1\Type\PrimitiveType;
use ASN1\Type\StringType;
use ASN1\Type\UniversalClass;


/**
 * Implements <i>BIT STRING</i> type.
 */
class BitString extends StringType
{
	use UniversalClass;
	use PrimitiveType;
	
	/**
	 * Number of unused bits in the last octet.
	 *
	 * @var int $_unusedBits
	 */
	protected $_unusedBits;
	
	/**
	 * Constructor
	 *
	 * @param string $string Content octets
	 * @param int $unused_bits Number of unused bits in the last octet
	 */
	public function __construct($string, $unused_bits = 0) {
		$this->_typeTag = self::TYPE_BIT_STRING;
		parent::__construct($string);
		$this->_unusedBits = $unused_bits;
	}
	
	/**
	 * Get the number of bits in the string.
	 *
	 * @return int
	 */
	public function numBits() {
		return strlen($this->_string) * 8 - $this->_unusedBits;
	}
	
	/**
	 * Get the number of unused bits in the last octet of the string.
	 *
	 * @return int
	 */
	public function unusedBits() {
		return $this->_unusedBits;
	}
	
	/**
	 * Test whether bit is set.
	 *
	 * @param int $idx Bit index.
	 *        Most significant bit of the first octet is index 0.
	 * @return boolean
	 */
	public function testBit($idx) {
		// octet index
		$oi = (int) floor($idx / 8);
		// if octet is outside range
		if ($oi < 0 || $oi >= strlen($this->_string)) {
			throw new \OutOfBoundsException("Index is out of bounds.");
		}
		// bit index
		$bi = $idx % 8;
		// if tested bit is last octet's unused bit
		if ($oi == strlen($this->_string) - 1) {
			if ($bi >= 8 - $this->_unusedBits) {
				throw new \OutOfBoundsException("Index refers to an unused bit.");
			}
		}
		$byte = $this->_string[$oi];
		// index 0 is the most significant bit in byte
		$mask = 0x01 << (7 - $bi);
		return (ord($byte) & $mask) > 0;
	}
	
	/**
	 * Get range of bits.
	 *
	 * @param int $start Index of first bit
	 * @param int $length Number of bits in range
	 * @throws \OutOfBoundsException
	 * @return number Integer of $length bits
	 */
	public function range($start, $length) {
		if (!$length) {
			return 0;
		}
		if ($start + $length > $this->numBits()) {
			throw new \OutOfBoundsException("Not enough bits.");
		}
		$bits = gmp_init(0);
		$idx = $start;
		$end = $start + $length;
		while (true) {
			$bit = $this->testBit($idx) ? 1 : 0;
			$bits |= $bit;
			if (++$idx >= $end) {
				break;
			}
			$bits <<= 1;
		}
		return gmp_strval($bits, 10);
	}
	
	/**
	 * Get a copy of the bit string with trailing zeroes removed.
	 *
	 * @return self
	 */
	public function withoutTrailingZeroes() {
		// if bit string was empty
		if (!strlen($this->_string)) {
			return new self("");
		}
		$bits = $this->_string;
		// count number of empty trailing octets
		$unused_octets = 0;
		for ($idx = strlen($bits) - 1; $idx >= 0; --$idx, ++$unused_octets) {
			if ($bits[$idx] != "\x0") {
				break;
			}
		}
		// strip trailing octets
		if ($unused_octets) {
			$bits = substr($bits, 0, -$unused_octets);
		}
		// if bit string was full of zeroes
		if (!strlen($bits)) {
			return new self("");
		}
		// count number of trailing zeroes in the last octet
		$unused_bits = 0;
		$byte = ord($bits[strlen($bits) - 1]);
		while (!($byte & 0x01)) {
			$unused_bits++;
			$byte >>= 1;
		}
		return new self($bits, $unused_bits);
	}
	
	protected function _encodedContentDER() {
		$der = chr($this->_unusedBits);
		$der .= $this->_string;
		if ($this->_unusedBits) {
			$octet = $der[strlen($der) - 1];
			// set unused bits to zero
			$octet &= chr(0xff & ~((1 << $this->_unusedBits) - 1));
			$der[strlen($der) - 1] = $octet;
		}
		return $der;
	}
	
	protected static function _decodeFromDER(Identifier $identifier, $data, 
			&$offset) {
		$idx = $offset;
		$length = Length::expectFromDER($data, $idx);
		if ($length->length() < 1) {
			throw new DecodeException("Bit string length must be at least 1.");
		}
		$unused_bits = ord($data[$idx++]);
		if ($unused_bits > 7) {
			throw new DecodeException(
				"Unused bits in a bit string must be less than 8.");
		}
		$str = substr($data, $idx, $length->length() - 1);
		if ($unused_bits) {
			$mask = (1 << $unused_bits) - 1;
			if (ord($str[strlen($str) - 1]) & $mask) {
				throw new DecodeException(
					"DER encoded bit string must have zero padding.");
			}
		}
		$offset = $idx + $length->length() - 1;
		return new self($str, $unused_bits);
	}
}
