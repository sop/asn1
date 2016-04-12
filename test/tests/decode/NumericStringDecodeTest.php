<?php

use ASN1\Type\Primitive\NumericString;


/**
 * @group decode
 */
class NumericStringDecodeTest extends PHPUnit_Framework_TestCase
{
	public function testType() {
		$el = NumericString::fromDER("\x12\x0");
		$this->assertInstanceOf('ASN1\Type\Primitive\NumericString', $el);
	}
	
	public function testValue() {
		$str = "123 456 789 0";
		$el = NumericString::fromDER("\x12\x0d$str");
		$this->assertEquals($str, $el->str());
	}
	
	/**
	 * @expectedException ASN1\Exception\DecodeException
	 */
	public function testInvalidValue() {
		$str = "123-456-789-0";
		NumericString::fromDER("\x12\x0d$str");
	}
}
