<?php

use ASN1\Element;
use ASN1\Type\Primitive\NullType;
use ASN1\Type\Primitive\NumericString;
use ASN1\Type\UnspecifiedType;


/**
 * @group type
 * @group numeric-string
 */
class NumericStringTest extends PHPUnit_Framework_TestCase
{
	public function testCreate() {
		$el = new NumericString("");
		$this->assertInstanceOf(NumericString::class, $el);
		return $el;
	}
	
	/**
	 * @depends testCreate
	 *
	 * @param Element $el
	 */
	public function testTag(Element $el) {
		$this->assertEquals(Element::TYPE_NUMERIC_STRING, $el->tag());
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
		$el = NumericString::fromDER($data);
		$this->assertInstanceOf(NumericString::class, $el);
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
	
	/**
	 * @depends testCreate
	 *
	 * @param Element $el
	 */
	public function testWrapped(Element $el) {
		$wrap = new UnspecifiedType($el);
		$this->assertInstanceOf(NumericString::class, $wrap->asNumericString());
	}
	
	/**
	 * @expectedException UnexpectedValueException
	 */
	public function testWrappedFail() {
		$wrap = new UnspecifiedType(new NullType());
		$wrap->asNumericString();
	}
}
