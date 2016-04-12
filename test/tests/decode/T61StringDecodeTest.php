<?php

use ASN1\Type\Primitive\T61String;


/**
 * @group decode
 */
class T61StringDecodeTest extends PHPUnit_Framework_TestCase
{
	public function testType() {
		$el = T61String::fromDER("\x14\x0");
		$this->assertInstanceOf('ASN1\Type\Primitive\T61String', $el);
	}
	
	public function testValue() {
		$str = "Hello World!";
		$el = T61String::fromDER("\x14\x0c$str");
		$this->assertEquals($str, $el->str());
	}
}
