<?php

use ASN1\Element;
use ASN1\Type\Primitive\NullType;
use ASN1\Type\Primitive\VideotexString;
use ASN1\Type\UnspecifiedType;


/**
 * @group type
 * @group videotex-string
 */
class VideotexStringTest extends PHPUnit_Framework_TestCase
{
	public function testCreate() {
		$el = new VideotexString("");
		$this->assertInstanceOf(VideotexString::class, $el);
		return $el;
	}
	
	/**
	 * @depends testCreate
	 *
	 * @param Element $el
	 */
	public function testTag(Element $el) {
		$this->assertEquals(Element::TYPE_VIDEOTEX_STRING, $el->tag());
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
		$el = VideotexString::fromDER($data);
		$this->assertInstanceOf(VideotexString::class, $el);
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
		$this->assertInstanceOf(VideotexString::class, 
			$wrap->asVideotexString());
	}
	
	/**
	 * @expectedException UnexpectedValueException
	 */
	public function testWrappedFail() {
		$wrap = new UnspecifiedType(new NullType());
		$wrap->asVideotexString();
	}
}
