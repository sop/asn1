<?php

namespace ASN1\Type\Primitive;

use ASN1\Component\Identifier;
use ASN1\Component\Length;
use ASN1\Element;
use ASN1\Type\PrimitiveType;
use ASN1\Type\UniversalClass;


/**
 * Implements <i>INTEGER</i> type.
 */
class Integer extends Element
{
	use UniversalClass;
	use PrimitiveType;
	
	/**
	 * Number as a base 10.
	 *
	 * @var int|string
	 */
	private $_number;
	
	/**
	 * Constructor
	 *
	 * @param int|string $number Base 10 integer
	 */
	public function __construct($number) {
		$this->_typeTag = self::TYPE_INTEGER;
		if (!self::_validateNumber($number)) {
			$var = is_scalar($number) ? strval($number) : gettype($number);
			throw new \InvalidArgumentException("'$var' is not a valid number.");
		}
		$this->_number = $number;
	}
	
	/**
	 * Get the number as a base 10.
	 *
	 * @return int|string
	 */
	public function number() {
		return $this->_number;
	}
	
	protected function _encodedContentDER() {
		$num = gmp_init($this->_number, 10);
		switch (gmp_sign($num)) {
		// positive
		case 1:
			return self::_encodePositiveInteger($num);
		// negative
		case -1:
			return self::_encodeNegativeInteger($num);
		}
		// zero
		return "\0";
	}
	
	/**
	 * Encode positive integer to DER content.
	 *
	 * @param \GMP|resource $num
	 * @return string
	 */
	private static function _encodePositiveInteger($num) {
		$bin = gmp_export($num, 1, GMP_MSW_FIRST | GMP_BIG_ENDIAN);
		// if first bit is 1, prepend full zero byte
		// to represent positive two's complement
		if (ord($bin[0]) & 0x80) {
			$bin = chr(0x00) . $bin;
		}
		return $bin;
	}
	
	/**
	 * Encode negative integer to DER content.
	 *
	 * @param \GMP|resource $num
	 * @return string
	 */
	private static function _encodeNegativeInteger($num) {
		$num = gmp_abs($num);
		// compute number of bytes required
		$width = 1;
		if ($num > 128) {
			$tmp = $num;
			do {
				$width++;
				$tmp >>= 8;
			} while ($tmp > 128);
		}
		// compute two's complement 2^n - x
		$num = gmp_pow("2", 8 * $width) - $num;
		$bin = gmp_export($num, 1, GMP_MSW_FIRST | GMP_BIG_ENDIAN);
		// if first bit is 0, prepend full inverted byte
		// to represent negative two's complement
		if (!(ord($bin[0]) & 0x80)) {
			$bin = chr(0xff) . $bin;
		}
		return $bin;
	}
	
	protected static function _decodeFromDER(Identifier $identifier, $data, 
			&$offset) {
		$idx = $offset;
		$length = Length::expectFromDER($data, $idx);
		$bytes = substr($data, $idx, $length->length());
		$idx += $length->length();
		$neg = ord($bytes[0]) & 0x80;
		// negative, apply inversion of two's complement
		if ($neg) {
			$len = strlen($bytes);
			for ($i = 0; $i < $len; $i++) {
				$bytes[$i] = ~$bytes[$i];
			}
		}
		$num = gmp_init(bin2hex($bytes), 16);
		// negative, apply addition of two's complement
		// and produce negative result
		if ($neg) {
			$num = gmp_neg($num + 1);
		}
		$offset = $idx;
		// late static binding since enumerated extends integer type
		return new static(gmp_strval($num, 10));
	}
	
	/**
	 * Test that number is valid for this context.
	 *
	 * @param mixed $num
	 * @return boolean
	 */
	private static function _validateNumber($num) {
		if (is_int($num)) {
			return true;
		}
		if (is_string($num) && preg_match('/-?\d+/', $num)) {
			return true;
		}
		return false;
	}
}
