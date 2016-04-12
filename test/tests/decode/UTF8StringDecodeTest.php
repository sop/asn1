<?php

use ASN1\Type\Primitive\UTF8String;


/**
 * @group decode
 */
class UTF8StringDecodeTest extends PHPUnit_Framework_TestCase
{
	public function testType() {
		$el = UTF8String::fromDER("\x0c\x0");
		$this->assertInstanceOf('ASN1\Type\Primitive\UTF8String', $el);
	}
	
	public function testValue() {
		$str = "⠠⠓⠑⠇⠇⠕ ⠠⠺⠕⠗⠇⠙!";
		$el = UTF8String::fromDER("\x0c\x26$str");
		$this->assertEquals($str, $el->str());
	}
	
	/**
	 * @expectedException ASN1\Exception\DecodeException
	 */
	public function testInvalidValue() {
		$str = "Hello W\x94rld!";
		UTF8String::fromDER("\x0c\x0c$str");
	}
}
