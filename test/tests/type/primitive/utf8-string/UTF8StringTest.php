<?php

use ASN1\Element;
use ASN1\Type\Primitive\UTF8String;


/**
 * @group type
 * @group utf8-string
 */
class UTF8StringTest extends PHPUnit_Framework_TestCase
{
	public function testCreate() {
		$el = new UTF8String("");
		$this->assertInstanceOf(UTF8String::class, $el);
		return $el;
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
		$el = UTF8String::fromDER($data);
		$this->assertInstanceOf(UTF8String::class, $el);
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
		new UTF8String(hex2bin("ff"));
	}
}
