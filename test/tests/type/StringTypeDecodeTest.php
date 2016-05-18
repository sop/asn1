<?php

use ASN1\Element;
use ASN1\Type\StringType;


/**
 * @group decode
 * @group string
 */
class StringTypeDecodeTest extends PHPUnit_Framework_TestCase
{
	public function testType() {
		$el = StringType::fromDER("\x13\x0");
		$this->assertInstanceOf(StringType::class, $el);
	}
	
	public function testValue() {
		$el = StringType::fromDER("\x13\x0bHello World");
		$this->assertEquals("Hello World", $el->string());
	}
	
	public function testExpectation() {
		$el = StringType::fromDER("\x13\x0bHello World");
		$el->expectType(Element::TYPE_STRING);
	}
	
	/**
	 * @expectedException ASN1\Exception\DecodeException
	 */
	public function testConstructedFail() {
		StringType::fromDER("\x34\x0");
	}
}
