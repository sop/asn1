<?php

namespace ASN1\Component;

use ASN1\Encodable;
use ASN1\Exception\DecodeException;


/**
 * Class to represent BER/DER identifier octets.
 */
class Identifier implements Encodable
{
	// Type class enumerations
	const CLASS_UNIVERSAL = 0b00;
	const CLASS_APPLICATION = 0b01;
	const CLASS_CONTEXT_SPECIFIC = 0b10;
	const CLASS_PRIVATE = 0b11;
	
	/**
	 * Mapping from type class to human readable name.
	 *
	 * @internal
	 *
	 * @var array
	 */
	const MAP_CLASS_TO_NAME = array(
		/* @formatter:off */
		self::CLASS_UNIVERSAL => "UNIVERSAL", 
		self::CLASS_APPLICATION => "APPLICATION", 
		self::CLASS_CONTEXT_SPECIFIC => "CONTEXT SPECIFIC", 
		self::CLASS_PRIVATE => "PRIVATE"
		/* @formatter:on */
	);
	
	// P/C enumerations
	const PRIMITIVE = 0b0;
	const CONSTRUCTED = 0b1;
	
	/**
	 * Type class.
	 *
	 * @var int
	 */
	private $_class;
	
	/**
	 * Primitive or Constructed.
	 *
	 * @var int
	 */
	private $_pc;
	
	/**
	 * Content type tag.
	 *
	 * @var int
	 */
	private $_tag;
	
	/**
	 * Constructor
	 *
	 * @param int $class Type class
	 * @param int $pc Privitive / Constructed
	 * @param int|string $tag Type tag
	 */
	public function __construct($class, $pc, $tag) {
		$this->_class = 0b11 & $class;
		$this->_pc = 0b1 & $pc;
		$this->_tag = $tag;
	}
	
	/**
	 * Decode identifier component from DER data.
	 *
	 * @param string $data DER encoded data
	 * @param int|null $offset Reference to the variable that contains offset
	 *        into the data where to start parsing. Variable is updated to
	 *        the offset next to the parsed identifier. If null, start from
	 *        offset 0.
	 * @throws DecodeException If decoding fails
	 * @return self
	 */
	public static function fromDER($data, &$offset = null) {
		assert('is_string($data)', "got " . gettype($data));
		$idx = $offset ? $offset : 0;
		$datalen = strlen($data);
		if ($idx >= $datalen) {
			throw new DecodeException("Invalid offset.");
		}
		$byte = ord($data[$idx++]);
		// bits 8 and 7 (class)
		// 0 = universal, 1 = application, 2 = context-specific, 3 = private
		$class = (0b11000000 & $byte) >> 6;
		// bit 6 (0 = primitive / 1 = constructed)
		$pc = (0b00100000 & $byte) >> 5;
		// bits 5 to 1 (tag number)
		$tag = (0b00011111 & $byte);
		// long-form identifier
		if (0x1f == $tag) {
			$tag = gmp_init(0, 10);
			while (true) {
				if ($idx >= $datalen) {
					throw new DecodeException(
						"Unexpected end of data while decoding" .
							 " long form identifier.");
				}
				$byte = ord($data[$idx++]);
				$tag <<= 7;
				$tag |= 0x7f & $byte;
				// last byte has bit 8 set to zero
				if (!(0x80 & $byte)) {
					break;
				}
			}
			$tag = gmp_strval($tag, 10);
		}
		if (isset($offset)) {
			$offset = $idx;
		}
		return new self($class, $pc, $tag);
	}
	
	/**
	 *
	 * @see Encodable::toDER()
	 * @return string
	 */
	public function toDER() {
		$bytes = array();
		$byte = $this->_class << 6 | $this->_pc << 5;
		$tag = gmp_init($this->_tag, 10);
		if ($tag < 0x1f) {
			$bytes[] = $byte | $tag;
		} else { // long-form identifier
			$bytes[] = $byte | 0x1f;
			$octets = array();
			for (; $tag > 0; $tag >>= 7) {
				array_push($octets, gmp_intval(0x80 | ($tag & 0x7f)));
			}
			// last octet has bit 8 set to zero
			$octets[0] &= 0x7f;
			foreach (array_reverse($octets) as $octet) {
				$bytes[] = $octet;
			}
		}
		return pack("C*", ...$bytes);
	}
	
	/**
	 * Get class of the type.
	 *
	 * @return int
	 */
	public function typeClass() {
		return $this->_class;
	}
	
	/**
	 * Get P/C.
	 *
	 * @return int
	 */
	public function pc() {
		return $this->_pc;
	}
	
	/**
	 * Get the tag number.
	 *
	 * @return int
	 */
	public function tag() {
		return $this->_tag;
	}
	
	/**
	 * Check whether type is of an universal class.
	 *
	 * @return boolean
	 */
	public function isUniversal() {
		return self::CLASS_UNIVERSAL == $this->_class;
	}
	
	/**
	 * Check whether type is of an application class.
	 *
	 * @return boolean
	 */
	public function isApplication() {
		return self::CLASS_APPLICATION == $this->_class;
	}
	
	/**
	 * Check whether type is of a context specific class.
	 *
	 * @return boolean
	 */
	public function isContextSpecific() {
		return self::CLASS_CONTEXT_SPECIFIC == $this->_class;
	}
	
	/**
	 * Check whether type is of a private class.
	 *
	 * @return boolean
	 */
	public function isPrivate() {
		return self::CLASS_PRIVATE == $this->_class;
	}
	
	/**
	 * Check whether content is primitive type.
	 *
	 * @return boolean
	 */
	public function isPrimitive() {
		return self::PRIMITIVE == $this->_pc;
	}
	
	/**
	 * Check hether content is constructed type.
	 *
	 * @return boolean
	 */
	public function isConstructed() {
		return self::CONSTRUCTED == $this->_pc;
	}
	
	/**
	 * Get self with given type class.
	 *
	 * @param int $class One of <code>CLASS_*</code> enumerations
	 * @return self
	 */
	public function withClass($class) {
		$obj = clone $this;
		$obj->_class = $class;
		return $obj;
	}
	
	/**
	 * Get self with given type tag.
	 *
	 * @param int $tag
	 * @return self
	 */
	public function withTag($tag) {
		$obj = clone $this;
		$obj->_tag = $tag;
		return $obj;
	}
	
	/**
	 * Get human readable name of the type class.
	 *
	 * @param int $class
	 * @return string
	 */
	public static function classToName($class) {
		if (!array_key_exists($class, self::MAP_CLASS_TO_NAME)) {
			return "CLASS $class";
		}
		return self::MAP_CLASS_TO_NAME[$class];
	}
}
