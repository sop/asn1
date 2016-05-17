<?php

namespace ASN1\Type;

use ASN1\Component\Identifier;
use ASN1\Component\Length;
use ASN1\Element;
use ASN1\Exception\DecodeException;


/**
 * Base class for constructed types.
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
	 * Mapping of tagged elements.
	 *
	 * @var array $_taggedMap
	 */
	private $_taggedMap;
	
	/**
	 * Constructor
	 *
	 * @param Element ...$elements Any number of elements
	 */
	public function __construct(Element ...$elements) {
		$this->_elements = $elements;
	}
	
	public function isConstructed() {
		return true;
	}
	
	protected function _encodedContentDER() {
		$data = "";
		foreach ($this->_elements as $element) {
			$data .= $element->toDER();
		}
		return $data;
	}
	
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
	 * Explode DER structure to DER encoded parts it contains.
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
	 * Get self with element at given index replaced by another.
	 *
	 * @param int $idx Element index
	 * @param Element $el New element to insert into structure
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
	 * Get self with element inserted before given index.
	 *
	 * @param int $idx Element index
	 * @param Element $el New element to insert into structure
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
	 * Get self with element appended to the end.
	 *
	 * @param Element $el Element to insert into structure
	 * @return self
	 */
	public function withAppended(Element $el) {
		$obj = clone $this;
		array_push($obj->_elements, $el);
		return $obj;
	}
	
	/**
	 * Get self with element prepended in the beginning.
	 *
	 * @param Element $el Element to insert into structure
	 * @return self
	 */
	public function withPrepended(Element $el) {
		$obj = clone $this;
		array_unshift($obj->_elements, $el);
		return $obj;
	}
	
	/**
	 * Get self with element at given index removed.
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
	 * @return Element[]
	 */
	public function elements() {
		return $this->_elements;
	}
	
	/**
	 * Check whether structure has an element at given index, optionally
	 * satisfying given tag expectation.
	 *
	 * @param int $idx
	 * @param int $expectedTag
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
	 * Get element at given index.
	 *
	 * Optionally check that the element has a given tag.
	 *
	 * @param int $idx Index, first element is 0
	 * @param int $expectedTag Type tag to expect
	 * @throws \OutOfBoundsException If element doesn't exists
	 * @throws \UnexpectedValueException If expectation fails
	 * @return Element
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
		return $element;
	}
	
	/**
	 * Check whether structure contains a context specific element with a given
	 * tag.
	 *
	 * @param int $tag
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
	 * Get context specific element tagged with a given tag.
	 *
	 * @param int $tag
	 * @throws \OutOfBoundsException
	 * @return Element
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
	 * Get iterator for the elements of the structure.
	 *
	 * @see IteratorAggregate::getIterator()
	 * @return \ArrayIterator
	 */
	public function getIterator() {
		return new \ArrayIterator($this->_elements);
	}
}
