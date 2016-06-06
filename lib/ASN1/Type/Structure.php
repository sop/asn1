<?php

namespace ASN1\Type;

use ASN1\Component\Identifier;
use ASN1\Component\Length;
use ASN1\Element;
use ASN1\Exception\DecodeException;
use ASN1\Type\UnspecifiedType;


/**
 * Base class for the constructed types.
 */
abstract class Structure extends Element implements \Countable, 
	\IteratorAggregate
{
	use UniversalClass;
	
	/**
	 * Array of elements in the structure.
	 *
	 * @var Element[] $_elements
	 */
	protected $_elements;
	
	/**
	 * Lookup table for the tagged elements.
	 *
	 * @var TaggedType[]|null $_taggedMap
	 */
	private $_taggedMap;
	
	/**
	 * Cache variable of elements wrapped into UnspecifiedType objects.
	 *
	 * @var UnspecifiedType[]|null $_unspecifiedTypes
	 */
	private $_unspecifiedTypes;
	
	/**
	 * Constructor
	 *
	 * @param Element ...$elements Any number of elements
	 */
	public function __construct(Element ...$elements) {
		$this->_elements = $elements;
	}
	
	/**
	 * Clone magic method.
	 */
	public function __clone() {
		// clear cache-variables
		$this->_taggedMap = null;
		$this->_unspecifiedTypes = null;
	}
	
	/**
	 *
	 * @see \ASN1\Element::isConstructed()
	 * @return bool
	 */
	public function isConstructed() {
		return true;
	}
	
	/**
	 *
	 * @see \ASN1\Element::_encodedContentDER()
	 * @return string
	 */
	protected function _encodedContentDER() {
		$data = "";
		foreach ($this->_elements as $element) {
			$data .= $element->toDER();
		}
		return $data;
	}
	
	/**
	 *
	 * @see \ASN1\Element::_decodeFromDER()
	 * @return self
	 */
	protected static function _decodeFromDER(Identifier $identifier, $data, 
			&$offset) {
		$idx = $offset;
		if (!$identifier->isConstructed()) {
			throw new DecodeException(
				"Structured element must have constructed bit set.");
		}
		$length = Length::expectFromDER($data, $idx);
		$end = $idx + $length->length();
		$elements = array();
		while ($idx < $end) {
			$elements[] = Element::fromDER($data, $idx);
			// check that element didn't overflow length
			if ($idx > $end) {
				throw new DecodeException(
					"Structure's content overflows length.");
			}
		}
		$offset = $idx;
		// return instance by static late binding
		return new static(...$elements);
	}
	
	/**
	 * Explode DER structure to DER encoded components that it contains.
	 *
	 * @param string $data
	 * @throws DecodeException
	 * @return string[]
	 */
	public static function explodeDER($data) {
		$offset = 0;
		$identifier = Identifier::fromDER($data, $offset);
		if (!$identifier->isConstructed()) {
			throw new DecodeException("Element is not constructed.");
		}
		$length = Length::expectFromDER($data, $offset);
		$end = $offset + $length->length();
		$parts = array();
		while ($offset < $end) {
			// start of the element
			$idx = $offset;
			// skip identifier
			Identifier::fromDER($data, $offset);
			// decode element length
			$length = Length::expectFromDER($data, $offset);
			// extract der encoding of the element
			$parts[] = substr($data, $idx, $offset - $idx + $length->length());
			// update offset over content
			$offset += $length->length();
		}
		return $parts;
	}
	
	/**
	 * Get self with an element at the given index replaced by another.
	 *
	 * @param int $idx Element index
	 * @param Element $el New element to insert into the structure
	 * @throws \OutOfBoundsException
	 * @return self
	 */
	public function withReplaced($idx, Element $el) {
		if (!isset($this->_elements[$idx])) {
			throw new \OutOfBoundsException(
				"Structure doesn't have element at index $idx.");
		}
		$obj = clone $this;
		$obj->_elements[$idx] = $el;
		return $obj;
	}
	
	/**
	 * Get self with an element inserted before the given index.
	 *
	 * @param int $idx Element index
	 * @param Element $el New element to insert into the structure
	 * @throws \OutOfBoundsException
	 * @return self
	 */
	public function withInserted($idx, Element $el) {
		if (count($this->_elements) < $idx || $idx < 0) {
			throw new \OutOfBoundsException("Index $idx is out of bounds.");
		}
		$obj = clone $this;
		array_splice($obj->_elements, $idx, 0, [$el]);
		return $obj;
	}
	
	/**
	 * Get self with an element appended to the end.
	 *
	 * @param Element $el Element to insert into the structure
	 * @return self
	 */
	public function withAppended(Element $el) {
		$obj = clone $this;
		array_push($obj->_elements, $el);
		return $obj;
	}
	
	/**
	 * Get self with an element prepended in the beginning.
	 *
	 * @param Element $el Element to insert into the structure
	 * @return self
	 */
	public function withPrepended(Element $el) {
		$obj = clone $this;
		array_unshift($obj->_elements, $el);
		return $obj;
	}
	
	/**
	 * Get self with an element at the given index removed.
	 *
	 * @param int $idx Element index
	 * @throws \OutOfBoundsException
	 * @return self
	 */
	public function withoutElement($idx) {
		if (!isset($this->_elements[$idx])) {
			throw new \OutOfBoundsException(
				"Structure doesn't have element at index $idx.");
		}
		$obj = clone $this;
		array_splice($obj->_elements, $idx, 1);
		return $obj;
	}
	
	/**
	 * Get elements in the structure.
	 *
	 * @return UnspecifiedType[]
	 */
	public function elements() {
		if (!isset($this->_unspecifiedTypes)) {
			$this->_unspecifiedTypes = array_map(
				function (Element $el) {
					return new UnspecifiedType($el);
				}, $this->_elements);
		}
		return $this->_unspecifiedTypes;
	}
	
	/**
	 * Check whether the structure has an element at the given index, optionally
	 * satisfying given tag expectation.
	 *
	 * @param int $idx Index 0..n
	 * @param int|null $expectedTag Optional type tag expectation
	 * @return bool
	 */
	public function has($idx, $expectedTag = null) {
		if (!isset($this->_elements[$idx])) {
			return false;
		}
		if (isset($expectedTag)) {
			if (!$this->_elements[$idx]->isType($expectedTag)) {
				return false;
			}
		}
		return true;
	}
	
	/**
	 * Get the element at the given index, optionally checking that the element
	 * has a given tag.
	 *
	 * NOTE! Expectation checking is deprecated and should be done
	 * with UnspecifiedType.
	 *
	 * @param int $idx Index 0..n
	 * @param int|null $expectedTag Optional type tag expectation
	 * @throws \OutOfBoundsException If element doesn't exists
	 * @throws \UnexpectedValueException If expectation fails
	 * @return UnspecifiedType
	 */
	public function at($idx, $expectedTag = null) {
		if (!isset($this->_elements[$idx])) {
			throw new \OutOfBoundsException(
				"Structure doesn't have an element at index $idx.");
		}
		$element = $this->_elements[$idx];
		if (isset($expectedTag)) {
			$element->expectType($expectedTag);
		}
		return new UnspecifiedType($element);
	}
	
	/**
	 * Check whether the structure contains a context specific element with a
	 * given tag.
	 *
	 * @param int $tag Tag number
	 * @return boolean
	 */
	public function hasTagged($tag) {
		// lazily build lookup map
		if (!isset($this->_taggedMap)) {
			$this->_taggedMap = array();
			foreach ($this->_elements as $element) {
				if ($element->isTagged()) {
					$this->_taggedMap[$element->tag()] = $element;
				}
			}
		}
		return isset($this->_taggedMap[$tag]);
	}
	
	/**
	 * Get a context specific element tagged with a given tag.
	 *
	 * @param int $tag
	 * @throws \LogicException If tag doesn't exists
	 * @return TaggedType
	 */
	public function getTagged($tag) {
		if (!$this->hasTagged($tag)) {
			throw new \LogicException("No tagged element for tag $tag.");
		}
		return $this->_taggedMap[$tag];
	}
	
	/**
	 *
	 * @see Countable::count()
	 * @return int
	 */
	public function count() {
		return count($this->_elements);
	}
	
	/**
	 * Get an iterator for the UnspecifiedElement objects.
	 *
	 * @see IteratorAggregate::getIterator()
	 * @return \ArrayIterator
	 */
	public function getIterator() {
		return new \ArrayIterator($this->elements());
	}
}
