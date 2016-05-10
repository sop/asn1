<?php

use ASN1\Element;
use ASN1\Type\Constructed\Sequence;
use ASN1\Type\Primitive\NullType;
use ASN1\Type\Structure;


/**
 * @group structure
 * @group sequence
 */
class SequenceTest extends PHPUnit_Framework_TestCase
{
	public function testCreate() {
		$seq = new Sequence(new NullType(), new NullType());
		$this->assertInstanceOf(Structure::class, $seq);
		return $seq;
	}
	
	/**
	 * @depends testCreate
	 *
	 * @param Sequence $seq
	 */
	public function testElements(Sequence $seq) {
		$elements = $seq->elements();
		$this->assertCount(2, $elements);
	}
	
	/**
	 * @depends testCreate
	 *
	 * @param Sequence $seq
	 */
	public function testCount(Sequence $seq) {
		$this->assertCount(2, $seq);
	}
	
	/**
	 * @depends testCreate
	 *
	 * @param Sequence $seq
	 */
	public function testIterator(Sequence $seq) {
		$elements = array();
		foreach ($seq as $el) {
			$elements[] = $el;
		}
		$this->assertCount(2, $elements);
	}
	
	/**
	 * @depends testCreate
	 *
	 * @param Sequence $seq
	 */
	public function testAt(Sequence $seq) {
		$el = $seq->at(1);
		$this->assertInstanceOf(NullType::class, $el);
	}
	
	/**
	 * @depends testCreate
	 * @expectedException OutOfBoundsException
	 *
	 * @param Sequence $seq
	 */
	public function testAtFail(Sequence $seq) {
		$seq->at(2);
	}
	
	/**
	 * @depends testCreate
	 * @expectedException UnexpectedValueException
	 *
	 * @param Sequence $seq
	 */
	public function testAtExpectationFail(Sequence $seq) {
		$seq->at(1, Element::TYPE_BOOLEAN);
	}
}
