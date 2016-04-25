<?php

namespace ASN1;

use ASN1\Component\Identifier;
use ASN1\Component\Length;
use ASN1\Type\Constructed\Sequence;
use ASN1\Type\Constructed\Set;
use ASN1\Type\Primitive\BitString;
use ASN1\Type\Primitive\BMPString;
use ASN1\Type\Primitive\Boolean;
use ASN1\Type\Primitive\CharacterString;
use ASN1\Type\Primitive\Enumerated;
use ASN1\Type\Primitive\GeneralizedTime;
use ASN1\Type\Primitive\GeneralString;
use ASN1\Type\Primitive\GraphicString;
use ASN1\Type\Primitive\IA5String;
use ASN1\Type\Primitive\Integer;
use ASN1\Type\Primitive\NullType;
use ASN1\Type\Primitive\NumericString;
use ASN1\Type\Primitive\ObjectIdentifier;
use ASN1\Type\Primitive\OctetString;
use ASN1\Type\Primitive\PrintableString;
use ASN1\Type\Primitive\Real;
use ASN1\Type\Primitive\T61String;
use ASN1\Type\Primitive\UniversalString;
use ASN1\Type\Primitive\UTCTime;
use ASN1\Type\Primitive\UTF8String;
use ASN1\Type\Primitive\VideotexString;
use ASN1\Type\Primitive\VisibleString;
use ASN1\Type\StringType;
use ASN1\Type\TaggedType;
use ASN1\Type\TimeType;


/**
 * Base class for all ASN.1 type elements.
 */
abstract class Element implements Encodable
{
	// Universal type tags
	const TYPE_EOC = 0x00;
	const TYPE_BOOLEAN = 0x01;
	const TYPE_INTEGER = 0x02;
	const TYPE_BIT_STRING = 0x03;
	const TYPE_OCTET_STRING = 0x04;
	const TYPE_NULL = 0x05;
	const TYPE_OBJECT_IDENTIFIER = 0x06;
	const TYPE_OBJECT_DESCRIPTOR = 0x07;
	const TYPE_EXTERNAL = 0x08;
	const TYPE_REAL = 0x09;
	const TYPE_ENUMERATED = 0x0a;
	const TYPE_EMBEDDED_PDV = 0x0b;
	const TYPE_UTF8_STRING = 0x0c;
	const TYPE_RELATIVE_OID = 0x0d;
	const TYPE_SEQUENCE = 0x10;
	const TYPE_SET = 0x11;
	const TYPE_NUMERIC_STRING = 0x12;
	const TYPE_PRINTABLE_STRING = 0x13;
	const TYPE_T61_STRING = 0x14;
	const TYPE_VIDEOTEX_STRING = 0x15;
	const TYPE_IA5_STRING = 0x16;
	const TYPE_UTC_TIME = 0x17;
	const TYPE_GENERALIZED_TIME = 0x18;
	const TYPE_GRAPHIC_STRING = 0x19;
	const TYPE_VISIBLE_STRING = 0x1a;
	const TYPE_GENERAL_STRING = 0x1b;
	const TYPE_UNIVERSAL_STRING = 0x1c;
	const TYPE_CHARACTER_STRING = 0x1d;
	const TYPE_BMP_STRING = 0x1e;
	
	/**
	 * Mapping from universal type tag to implementation class name.
	 *
	 * @var array
	 */
	private static $_tagToCls = array(
		/* @formatter:off */
		self::TYPE_BOOLEAN => Boolean::class,
		self::TYPE_INTEGER => Integer::class,
		self::TYPE_BIT_STRING => BitString::class,
		self::TYPE_OCTET_STRING => OctetString::class,
		self::TYPE_NULL => NullType::class,
		self::TYPE_OBJECT_IDENTIFIER => ObjectIdentifier::class,
		self::TYPE_REAL => Real::class,
		self::TYPE_ENUMERATED => Enumerated::class,
		self::TYPE_UTF8_STRING => UTF8String::class,
		self::TYPE_SEQUENCE => Sequence::class,
		self::TYPE_SET => Set::class,
		self::TYPE_NUMERIC_STRING => NumericString::class,
		self::TYPE_PRINTABLE_STRING => PrintableString::class,
		self::TYPE_T61_STRING => T61String::class,
		self::TYPE_VIDEOTEX_STRING => VideotexString::class,
		self::TYPE_IA5_STRING => IA5String::class,
		self::TYPE_UTC_TIME => UTCTime::class,
		self::TYPE_GENERALIZED_TIME => GeneralizedTime::class,
		self::TYPE_GRAPHIC_STRING => GraphicString::class,
		self::TYPE_VISIBLE_STRING => VisibleString::class,
		self::TYPE_GENERAL_STRING => GeneralString::class,
		self::TYPE_UNIVERSAL_STRING => UniversalString::class,
		self::TYPE_CHARACTER_STRING => CharacterString::class,
		self::TYPE_BMP_STRING => BMPString::class
		/* @formatter:on */
	);
	
	/**
	 * Pseudotype for all string types.
	 * May be used as an expectation parameter.
	 *
	 * @var int
	 */
	const TYPE_STRING = -1;
	
	/**
	 * Pseudotype for all time types.
	 * May be used as an expectation parameter.
	 *
	 * @var int
	 */
	const TYPE_TIME = -2;
	
	/**
	 * Human readable names for universal type tags.
	 *
	 * @var array
	 */
	private static $_typeNames = array(
		/* @formatter:off */
		self::TYPE_EOC => "EOC",
		self::TYPE_BOOLEAN => "BOOLEAN",
		self::TYPE_INTEGER => "INTEGER",
		self::TYPE_BIT_STRING => "BIT STRING",
		self::TYPE_OCTET_STRING => "OCTET STRING",
		self::TYPE_NULL => "NULL",
		self::TYPE_OBJECT_IDENTIFIER => "OBJECT IDENTIFIER",
		self::TYPE_OBJECT_DESCRIPTOR => "Object Descriptor",
		self::TYPE_EXTERNAL => "EXTERNAL",
		self::TYPE_REAL => "REAL",
		self::TYPE_ENUMERATED => "ENUMERATED",
		self::TYPE_EMBEDDED_PDV => "EMBEDDED PDV",
		self::TYPE_UTF8_STRING => "UTF8String",
		self::TYPE_RELATIVE_OID => "RELATIVE-OID",
		self::TYPE_SEQUENCE => "SEQUENCE",
		self::TYPE_SET => "SET",
		self::TYPE_NUMERIC_STRING => "NumericString",
		self::TYPE_PRINTABLE_STRING => "PrintableString",
		self::TYPE_T61_STRING => "T61String",
		self::TYPE_VIDEOTEX_STRING => "VideotexString",
		self::TYPE_IA5_STRING => "IA5String",
		self::TYPE_UTC_TIME => "UTCTime",
		self::TYPE_GENERALIZED_TIME => "GeneralizedTime",
		self::TYPE_GRAPHIC_STRING => "GraphicString",
		self::TYPE_VISIBLE_STRING => "VisibleString",
		self::TYPE_GENERAL_STRING => "GeneralString",
		self::TYPE_UNIVERSAL_STRING => "UniversalString",
		self::TYPE_CHARACTER_STRING => "CHARACTER STRING",
		self::TYPE_BMP_STRING => "BMPString",
		self::TYPE_STRING => "Any String",
		self::TYPE_TIME => "Any Time"
		/* @formatter:on */
	);
	
	/**
	 * Element's type tag.
	 *
	 * @var int
	 */
	protected $_typeTag;
	
	/**
	 * Get class of the type.
	 * One of CLASS_* constants.
	 *
	 * @return int
	 */
	abstract public function typeClass();
	
	/**
	 * Whether type is constructed.
	 * Otherwise it's primitive.
	 *
	 * @return bool
	 */
	abstract public function isConstructed();
	
	/**
	 * Get content encoded in DER.
	 *
	 * @return string
	 */
	abstract protected function _encodedContentDER();
	
	/**
	 * Decode type-specific element from DER.
	 *
	 * @param Identifier $identifier Pre-parsed identifier
	 * @param string $data DER data
	 * @param int $offset Offset in data to the next byte after identifier
	 * @return Element
	 */
	protected static function _decodeFromDER(Identifier $identifier, $data, 
			&$offset) {
		throw new \BadMethodCallException();
	}
	
	/**
	 * Get tag of the element.
	 *
	 * @return int
	 */
	public function tag() {
		return $this->_typeTag;
	}
	
	/**
	 * Decode element from DER data.
	 *
	 * @param string $data DER encoded data
	 * @param int|null $offset Reference to the variable that contains offset
	 *        into the data where to start parsing. Variable is updated to
	 *        the offset next to the parsed element. If null, start from offset
	 *        0.
	 * @return self
	 */
	public static function fromDER($data, &$offset = null) {
		assert('is_string($data)', "got " . gettype($data));
		// decode identifier
		$idx = $offset ? $offset : 0;
		$identifier = Identifier::fromDER($data, $idx);
		// determine class that implements type specific decoding
		$cls = self::_determineImplClass($identifier);
		// decode remaining element
		$element = $cls::_decodeFromDER($identifier, $data, $idx);
		// if called in context of a concrete class, check
		// that decoded type matches the type of a calling class
		$called_class = get_called_class();
		if ($called_class != __CLASS__) {
			if (!($element instanceof $called_class)) {
				throw new \UnexpectedValueException(
					"$called_class expected, got " . get_class($element));
			}
		}
		if (isset($offset)) {
			$offset = $idx;
		}
		return $element;
	}
	
	/**
	 * Determine class that implements type
	 *
	 * @param Identifier $identifier
	 * @return string Class name
	 */
	protected static function _determineImplClass(Identifier $identifier) {
		// tagged type
		if ($identifier->isContextSpecific()) {
			return TaggedType::class;
		}
		if ($identifier->isUniversal()) {
			if (isset(self::$_tagToCls[$identifier->tag()])) {
				return self::$_tagToCls[$identifier->tag()];
			}
		}
		throw new \UnexpectedValueException(
			Identifier::classToName($identifier->typeClass()) . " " .
				 self::tagToName($identifier->tag()) . " not implemented");
	}
	
	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see Encodable::toDER()
	 * @return string
	 */
	public function toDER() {
		$identifier = new Identifier($this->typeClass(), 
			$this->isConstructed() ? Identifier::CONSTRUCTED : Identifier::PRIMITIVE, 
			$this->_typeTag);
		$content = $this->_encodedContentDER();
		$length = new Length(strlen($content));
		return $identifier->toDER() . $length->toDER() . $content;
	}
	
	/**
	 * Test whether element is a type of given tag
	 *
	 * @param int $tag
	 * @return boolean
	 */
	public function isType($tag) {
		// concrete type
		if ($tag >= 0 && $this->tag() == $tag) {
			return true;
		} else if (self::TYPE_STRING == $tag) { // string type
			if ($this instanceof StringType) {
				return true;
			}
		} else if (self::TYPE_TIME == $tag) { // time type
			if ($this instanceof TimeType) {
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Test whether element is tagged (context specific).
	 *
	 * @return bool
	 */
	public function isTagged() {
		return $this->typeClass() == Identifier::CLASS_CONTEXT_SPECIFIC;
	}
	
	/**
	 * Test whether element is a type of given tag.
	 * Throws an exception if expectation fails.
	 *
	 * @param int $tag
	 * @throws \UnexpectedValueException
	 * @return self
	 */
	public function expectType($tag) {
		if (!$this->isType($tag)) {
			throw new \UnexpectedValueException(
				self::tagToName($tag) . " expected, got " .
					 self::tagToName($this->_typeTag));
		}
		return $this;
	}
	
	/**
	 * Test whether element is tagged (context specific)
	 * and optionally has given tag.
	 *
	 * @param int|null $tag
	 * @throws \UnexpectedValueException
	 * @return self
	 */
	public function expectTagged($tag = null) {
		if (!$this->isTagged()) {
			throw new \UnexpectedValueException(
				"Context specific element expected, got " .
					 Identifier::classToName($this->typeClass()));
		}
		if (isset($tag) && $this->tag() != $tag) {
			throw new \UnexpectedValueException(
				"Tag $tag expected, got " . $this->tag());
		}
		return $this;
	}
	
	/**
	 * Get name for an universal tag
	 *
	 * @param int $tag
	 * @return string
	 */
	public static function tagToName($tag) {
		if (!isset(self::$_typeNames[$tag])) {
			return "TAG $tag";
		}
		return self::$_typeNames[$tag];
	}
}
