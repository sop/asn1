<?php

use ASN1\Element;
use ASN1\Type\Constructed\Set;
use ASN1\Type\Primitive\Boolean;
use ASN1\Type\Primitive\NullType;
use ASN1\Type\Structure;
use ASN1\Type\UnspecifiedType;


/**
 * @group structure
 * @group set
 */
class SetTest extends PHPUnit_Framework_TestCase
{
	public function testCreate() {
		$set = new Set(new NullType(), new Boolean(true));
		$this->assertInstanceOf(Structure::class, $set);
		return $set;
	}
	
	/**
	 * @depends testCreate
	 *
	 * @param Element $el
	 */
	public function testTag(Element $el) {
		$this->assertEquals(Element::TYPE_SET, $el->tag());
	}
	
	/**
	 * @depends testCreate
	 *
	 * @param Element $el
	 */
	public function testEncode(Element $el) {
		$der = $el->toDER();
		$this->assertInternalType("string", $der);
		return $der;
	}
	
	/**
	 * @depends testEncode
	 *
	 * @param string $data
	 */
	public function testDecode($data) {
		$el = Set::fromDER($data);
		$this->assertInstanceOf(Set::class, $el);
		return $el;
	}
	
	/**
	 * @depends testCreate
	 * @depends testDecode
	 *
	 * @param Element $ref
	 * @param Element $el
	 */
	public function testRecoded(Element $ref, Element $el) {
		$this->assertEquals($ref, $el);
	}
	
	public function testSortSame() {
		$set = new Set(new NullType(), new NullType());
		$sorted = $set->sortedSet();
		$this->assertEquals($set, $sorted);
	}
	
	/**
	 * @depends testCreate
	 *
	 * @param Element $el
	 */
	public function testWrapped(Element $el) {
		$wrap = new UnspecifiedType($el);
		$this->assertInstanceOf(Set::class, $wrap->asSet());
	}
	
	/**
	 * @expectedException UnexpectedValueException
	 */
	public function testWrappedFail() {
		$wrap = new UnspecifiedType(new NullType());
		$wrap->asSet();
	}
}
