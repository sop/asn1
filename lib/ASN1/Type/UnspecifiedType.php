<?php

namespace ASN1\Type;

use ASN1\Component\Identifier;
use ASN1\Element;
use ASN1\Feature\ElementBase;
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
use ASN1\Type\Primitive\ObjectDescriptor;
use ASN1\Type\Primitive\ObjectIdentifier;
use ASN1\Type\Primitive\OctetString;
use ASN1\Type\Primitive\PrintableString;
use ASN1\Type\Primitive\Real;
use ASN1\Type\Primitive\RelativeOID;
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
 * Decorator class to wrap an element without already knowing the specific
 * underlying type.
 *
 * Provides accessor methods to test the underlying type and return a type
 * hinted instance of the concrete element.
 */
class UnspecifiedType implements ElementBase
{
	/**
	 * Wrapped element.
	 *
	 * @var Element
	 */
	private $_element;
	
	/**
	 * Constructor
	 *
	 * @param Element $el
	 */
	public function __construct(Element $el) {
		$this->_element = $el;
	}
	
	/**
	 * Compatibility method to dispatch calls to wrapped element.
	 *
	 * @deprecated Use <code>as*</code> accessor methods to ensure strict type
	 * @param string $mtd Method name
	 * @param array $args Arguments
	 * @return mixed
	 */
	public function __call($mtd, array $args) {
		return call_user_func_array([$this->_element, $mtd], $args);
	}
	
	/**
	 * Get the wrapped element as an abstract type.
	 *
	 * @return ElementBase
	 */
	public function asElement() {
		return $this->_element;
	}
	
	/**
	 * Get the wrapped element as a context specific tagged type.
	 *
	 * @throws \UnexpectedValueException
	 * @return TaggedType
	 */
	public function asTagged() {
		if (!$this->_element instanceof TaggedType) {
			throw new \UnexpectedValueException(
				"Tagged element expected, got " . $this->_typeDescriptorString());
		}
		return $this->_element;
	}
	
	/**
	 * Get the wrapped element as a boolean type.
	 *
	 * @throws \UnexpectedValueException
	 * @return Boolean
	 */
	public function asBoolean() {
		if (!$this->_element instanceof Boolean) {
			throw new \UnexpectedValueException(
				$this->_generateExceptionMessage(Element::TYPE_BOOLEAN));
		}
		return $this->_element;
	}
	
	/**
	 * Get the wrapped element as an integer type.
	 *
	 * @throws \UnexpectedValueException
	 * @return Integer
	 */
	public function asInteger() {
		if (!$this->_element instanceof Integer) {
			throw new \UnexpectedValueException(
				$this->_generateExceptionMessage(Element::TYPE_INTEGER));
		}
		return $this->_element;
	}
	
	/**
	 * Get the wrapped element as a bit string type.
	 *
	 * @throws \UnexpectedValueException
	 * @return BitString
	 */
	public function asBitString() {
		if (!$this->_element instanceof BitString) {
			throw new \UnexpectedValueException(
				$this->_generateExceptionMessage(Element::TYPE_BIT_STRING));
		}
		return $this->_element;
	}
	
	/**
	 * Get the wrapped element as an octet string type.
	 *
	 * @throws \UnexpectedValueException
	 * @return OctetString
	 */
	public function asOctetString() {
		if (!$this->_element instanceof OctetString) {
			throw new \UnexpectedValueException(
				$this->_generateExceptionMessage(Element::TYPE_OCTET_STRING));
		}
		return $this->_element;
	}
	
	/**
	 * Get the wrapped element as a null type.
	 *
	 * @throws \UnexpectedValueException
	 * @return NullType
	 */
	public function asNull() {
		if (!$this->_element instanceof NullType) {
			throw new \UnexpectedValueException(
				$this->_generateExceptionMessage(Element::TYPE_NULL));
		}
		return $this->_element;
	}
	
	/**
	 * Get the wrapped element as an object identifier type.
	 *
	 * @throws \UnexpectedValueException
	 * @return ObjectIdentifier
	 */
	public function asObjectIdentifier() {
		if (!$this->_element instanceof ObjectIdentifier) {
			throw new \UnexpectedValueException(
				$this->_generateExceptionMessage(
					Element::TYPE_OBJECT_IDENTIFIER));
		}
		return $this->_element;
	}
	
	/**
	 * Get the wrapped element as an object descriptor type.
	 *
	 * @throws \UnexpectedValueException
	 * @return ObjectDescriptor
	 */
	public function asObjectDescriptor() {
		if (!$this->_element instanceof ObjectDescriptor) {
			throw new \UnexpectedValueException(
				$this->_generateExceptionMessage(
					Element::TYPE_OBJECT_DESCRIPTOR));
		}
		return $this->_element;
	}
	
	/**
	 * Get the wrapped element as a real type.
	 *
	 * @throws \UnexpectedValueException
	 * @return Real
	 */
	public function asReal() {
		if (!$this->_element instanceof Real) {
			throw new \UnexpectedValueException(
				$this->_generateExceptionMessage(Element::TYPE_REAL));
		}
		return $this->_element;
	}
	
	/**
	 * Get the wrapped element as an enumerated type.
	 *
	 * @throws \UnexpectedValueException
	 * @return Enumerated
	 */
	public function asEnumerated() {
		if (!$this->_element instanceof Enumerated) {
			throw new \UnexpectedValueException(
				$this->_generateExceptionMessage(Element::TYPE_ENUMERATED));
		}
		return $this->_element;
	}
	
	/**
	 * Get the wrapped element as a UTF8 string type.
	 *
	 * @throws \UnexpectedValueException
	 * @return UTF8String
	 */
	public function asUTF8String() {
		if (!$this->_element instanceof UTF8String) {
			throw new \UnexpectedValueException(
				$this->_generateExceptionMessage(Element::TYPE_UTF8_STRING));
		}
		return $this->_element;
	}
	
	/**
	 * Get the wrapped element as a relative OID type.
	 *
	 * @throws \UnexpectedValueException
	 * @return RelativeOID
	 */
	public function asRelativeOID() {
		if (!$this->_element instanceof RelativeOID) {
			throw new \UnexpectedValueException(
				$this->_generateExceptionMessage(Element::TYPE_RELATIVE_OID));
		}
		return $this->_element;
	}
	
	/**
	 * Get the wrapped element as a sequence type.
	 *
	 * @throws \UnexpectedValueException
	 * @return Sequence
	 */
	public function asSequence() {
		if (!$this->_element instanceof Sequence) {
			throw new \UnexpectedValueException(
				$this->_generateExceptionMessage(Element::TYPE_SEQUENCE));
		}
		return $this->_element;
	}
	
	/**
	 * Get the wrapped element as a set type.
	 *
	 * @throws \UnexpectedValueException
	 * @return Set
	 */
	public function asSet() {
		if (!$this->_element instanceof Set) {
			throw new \UnexpectedValueException(
				$this->_generateExceptionMessage(Element::TYPE_SET));
		}
		return $this->_element;
	}
	
	/**
	 * Get the wrapped element as a numeric string type.
	 *
	 * @throws \UnexpectedValueException
	 * @return NumericString
	 */
	public function asNumericString() {
		if (!$this->_element instanceof NumericString) {
			throw new \UnexpectedValueException(
				$this->_generateExceptionMessage(Element::TYPE_NUMERIC_STRING));
		}
		return $this->_element;
	}
	
	/**
	 * Get the wrapped element as a printable string type.
	 *
	 * @throws \UnexpectedValueException
	 * @return PrintableString
	 */
	public function asPrintableString() {
		if (!$this->_element instanceof PrintableString) {
			throw new \UnexpectedValueException(
				$this->_generateExceptionMessage(Element::TYPE_PRINTABLE_STRING));
		}
		return $this->_element;
	}
	
	/**
	 * Get the wrapped element as a T61 string type.
	 *
	 * @throws \UnexpectedValueException
	 * @return T61String
	 */
	public function asT61String() {
		if (!$this->_element instanceof T61String) {
			throw new \UnexpectedValueException(
				$this->_generateExceptionMessage(Element::TYPE_T61_STRING));
		}
		return $this->_element;
	}
	
	/**
	 * Get the wrapped element as a videotex string type.
	 *
	 * @throws \UnexpectedValueException
	 * @return VideotexString
	 */
	public function asVideotexString() {
		if (!$this->_element instanceof VideotexString) {
			throw new \UnexpectedValueException(
				$this->_generateExceptionMessage(Element::TYPE_VIDEOTEX_STRING));
		}
		return $this->_element;
	}
	
	/**
	 * Get the wrapped element as a IA6 string type.
	 *
	 * @throws \UnexpectedValueException
	 * @return IA5String
	 */
	public function asIA5String() {
		if (!$this->_element instanceof IA5String) {
			throw new \UnexpectedValueException(
				$this->_generateExceptionMessage(Element::TYPE_IA5_STRING));
		}
		return $this->_element;
	}
	
	/**
	 * Get the wrapped element as an UTC time type.
	 *
	 * @throws \UnexpectedValueException
	 * @return UTCTime
	 */
	public function asUTCTime() {
		if (!$this->_element instanceof UTCTime) {
			throw new \UnexpectedValueException(
				$this->_generateExceptionMessage(Element::TYPE_UTC_TIME));
		}
		return $this->_element;
	}
	
	/**
	 * Get the wrapped element as a generalized time type.
	 *
	 * @throws \UnexpectedValueException
	 * @return GeneralizedTime
	 */
	public function asGeneralizedTime() {
		if (!$this->_element instanceof GeneralizedTime) {
			throw new \UnexpectedValueException(
				$this->_generateExceptionMessage(Element::TYPE_GENERALIZED_TIME));
		}
		return $this->_element;
	}
	
	/**
	 * Get the wrapped element as a graphic string type.
	 *
	 * @throws \UnexpectedValueException
	 * @return GraphicString
	 */
	public function asGraphicString() {
		if (!$this->_element instanceof GraphicString) {
			throw new \UnexpectedValueException(
				$this->_generateExceptionMessage(Element::TYPE_GRAPHIC_STRING));
		}
		return $this->_element;
	}
	
	/**
	 * Get the wrapped element as a visible string type.
	 *
	 * @throws \UnexpectedValueException
	 * @return VisibleString
	 */
	public function asVisibleString() {
		if (!$this->_element instanceof VisibleString) {
			throw new \UnexpectedValueException(
				$this->_generateExceptionMessage(Element::TYPE_VISIBLE_STRING));
		}
		return $this->_element;
	}
	
	/**
	 * Get the wrapped element as a general string type.
	 *
	 * @throws \UnexpectedValueException
	 * @return GeneralString
	 */
	public function asGeneralString() {
		if (!$this->_element instanceof GeneralString) {
			throw new \UnexpectedValueException(
				$this->_generateExceptionMessage(Element::TYPE_GENERAL_STRING));
		}
		return $this->_element;
	}
	
	/**
	 * Get the wrapped element as a universal string type.
	 *
	 * @throws \UnexpectedValueException
	 * @return UniversalString
	 */
	public function asUniversalString() {
		if (!$this->_element instanceof UniversalString) {
			throw new \UnexpectedValueException(
				$this->_generateExceptionMessage(Element::TYPE_UNIVERSAL_STRING));
		}
		return $this->_element;
	}
	
	/**
	 * Get the wrapped element as a character string type.
	 *
	 * @throws \UnexpectedValueException
	 * @return CharacterString
	 */
	public function asCharacterString() {
		if (!$this->_element instanceof CharacterString) {
			throw new \UnexpectedValueException(
				$this->_generateExceptionMessage(Element::TYPE_CHARACTER_STRING));
		}
		return $this->_element;
	}
	
	/**
	 * Get the wrapped element as a BMP string type.
	 *
	 * @throws \UnexpectedValueException
	 * @return BMPString
	 */
	public function asBMPString() {
		if (!$this->_element instanceof BMPString) {
			throw new \UnexpectedValueException(
				$this->_generateExceptionMessage(Element::TYPE_BMP_STRING));
		}
		return $this->_element;
	}
	
	/**
	 * Get the wrapped element as any string type.
	 *
	 * @throws \UnexpectedValueException
	 * @return StringType
	 */
	public function asString() {
		if (!$this->_element instanceof StringType) {
			throw new \UnexpectedValueException(
				$this->_generateExceptionMessage(Element::TYPE_STRING));
		}
		return $this->_element;
	}
	
	/**
	 * Get the wrapped element as any time type.
	 *
	 * @throws \UnexpectedValueException
	 * @return TimeType
	 */
	public function asTime() {
		if (!$this->_element instanceof TimeType) {
			throw new \UnexpectedValueException(
				$this->_generateExceptionMessage(Element::TYPE_TIME));
		}
		return $this->_element;
	}
	
	/**
	 * Generate message for exceptions thrown by <code>as*</code> methods.
	 *
	 * @param int $tag Type tag of the expected element
	 * @return string
	 */
	private function _generateExceptionMessage($tag) {
		return Element::tagToName($tag) . " expected, got " .
			 $this->_typeDescriptorString() . ".";
	}
	
	/**
	 * Get textual description of the wrapped element for debugging purposes.
	 *
	 * @return string
	 */
	private function _typeDescriptorString() {
		$type_cls = $this->_element->typeClass();
		$tag = $this->_element->tag();
		if ($type_cls == Identifier::CLASS_UNIVERSAL) {
			return Element::tagToName($tag);
		}
		return Identifier::classToName($type_cls) . " TAG $tag";
	}
	
	/**
	 *
	 * @see \ASN1\Feature\Encodable::toDER()
	 * @return string
	 */
	public function toDER() {
		return $this->_element->toDER();
	}
	
	/**
	 *
	 * @see \ASN1\Feature\ElementBase::typeClass()
	 * @return int
	 */
	public function typeClass() {
		return $this->_element->typeClass();
	}
	
	/**
	 *
	 * @see \ASN1\Feature\ElementBase::isConstructed()
	 * @return bool
	 */
	public function isConstructed() {
		return $this->_element->isConstructed();
	}
	
	/**
	 *
	 * @see \ASN1\Feature\ElementBase::tag()
	 * @return int
	 */
	public function tag() {
		return $this->_element->tag();
	}
	
	/**
	 *
	 * @see \ASN1\Feature\ElementBase::isType()
	 * @return bool
	 */
	public function isType($tag) {
		return $this->_element->isType($tag);
	}
	
	/**
	 *
	 * @deprecated Use any <code>as*</code> accessor method first to ensure
	 *             type strictness.
	 * @see \ASN1\Feature\ElementBase::expectType()
	 * @return ElementBase
	 */
	public function expectType($tag) {
		return $this->_element->expectType($tag);
	}
	
	/**
	 *
	 * @see \ASN1\Feature\ElementBase::isTagged()
	 * @return bool
	 */
	public function isTagged() {
		return $this->_element->isTagged();
	}
	
	/**
	 *
	 * @deprecated Use any <code>as*</code> accessor method first to ensure
	 *             type strictness.
	 * @see \ASN1\Feature\ElementBase::expectTagged()
	 * @return TaggedType
	 */
	public function expectTagged($tag = null) {
		return $this->_element->expectTagged($tag);
	}
}
