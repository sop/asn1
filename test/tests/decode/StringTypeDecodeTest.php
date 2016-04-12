<?php

use ASN1\Type\StringType;
use ASN1\Element;


/**
 * @group decode
 */
class StringTypeDecodeTest extends PHPUnit_Framework_TestCase
{
	public function testType() {
		$el = StringType::fromDER("\x13\x0");
		$this->assertInstanceOf('ASN1\Type\StringType', $el);
	}
	
	public function testValue() {
		$el = StringType::fromDER("\x13\x0bHello World");
		$this->assertEquals("Hello World", $el->str());
	}
	
	public function testExpectation() {
		$el = StringType::fromDER("\x13\x0bHello World");
		$el->expectType(Element::TYPE_STRING);
	}
}
