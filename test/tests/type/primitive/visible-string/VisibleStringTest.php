<?php

use ASN1\Element;
use ASN1\ElementWrapper;
use ASN1\Type\Primitive\NullType;
use ASN1\Type\Primitive\VisibleString;


/**
 * @group type
 * @group visible-string
 */
class VisibleStringTest extends PHPUnit_Framework_TestCase
{
	public function testCreate() {
		$el = new VisibleString("");
		$this->assertInstanceOf(VisibleString::class, $el);
		return $el;
	}
	
	/**
	 * @depends testCreate
	 *
	 * @param Element $el
	 */
	public function testTag(Element $el) {
		$this->assertEquals(Element::TYPE_VISIBLE_STRING, $el->tag());
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
		$el = VisibleString::fromDER($data);
		$this->assertInstanceOf(VisibleString::class, $el);
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
		$wrap = new ElementWrapper($el);
		$this->assertInstanceOf(VisibleString::class, $wrap->asVisibleString());
	}
	
	/**
	 * @expectedException UnexpectedValueException
	 */
	public function testWrappedFail() {
		$wrap = new ElementWrapper(new NullType());
		$wrap->asVisibleString();
	}
}
