<?php

use ASN1\Type\Primitive\GeneralString;


/**
 * @group decode
 */
class GeneralStringDecodeTest extends PHPUnit_Framework_TestCase
{
	public function testType() {
		$el = GeneralString::fromDER("\x1b\x0");
		$this->assertInstanceOf('ASN1\Type\Primitive\GeneralString', $el);
	}
	
	public function testValue() {
		$str = "Hello World!";
		$el = GeneralString::fromDER("\x1b\x0c$str");
		$this->assertEquals($str, $el->str());
	}
}
