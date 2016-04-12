<?php

use ASN1\Type\Primitive\IA5String;


/**
 * @group decode
 */
class IA5StringDecodeTest extends PHPUnit_Framework_TestCase
{
	public function testType() {
		$el = IA5String::fromDER("\x16\x0");
		$this->assertInstanceOf('ASN1\Type\Primitive\IA5String', $el);
	}
	
	public function testValue() {
		$str = "Hello World!";
		$el = IA5String::fromDER("\x16\x0c$str");
		$this->assertEquals($str, $el->str());
	}
	
	/**
	 * @expectedException ASN1\Exception\DecodeException
	 */
	public function testInvalidValue() {
		$str = "H\xebll\xf8 W\xf6rld!";
		IA5String::fromDER("\x16\x0c$str");
	}
}
