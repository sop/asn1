<?php

use ASN1\Element;
use ASN1\Type\Primitive\UniversalString;


/**
 * @group type
 * @group universal-string
 */
class UniversalStringTest extends PHPUnit_Framework_TestCase
{
	public function testCreate() {
		$el = new UniversalString("");
		$this->assertInstanceOf(UniversalString::class, $el);
		return $el;
	}
	
	/**
	 * @depends testCreate
	 *
	 * @param Element $el
	 */
	public function testTag(Element $el) {
		$this->assertEquals(Element::TYPE_UNIVERSAL_STRING, $el->tag());
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
		$el = UniversalString::fromDER($data);
		$this->assertInstanceOf(UniversalString::class, $el);
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
	 * @expectedException InvalidArgumentException
	 */
	public function testInvalidString() {
		new UniversalString("xxx");
	}
}
