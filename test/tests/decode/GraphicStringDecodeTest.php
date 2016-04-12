<?php

use ASN1\Type\Primitive\GraphicString;


/**
 * @group decode
 */
class GraphicStringDecodeTest extends PHPUnit_Framework_TestCase
{
	public function testType() {
		$el = GraphicString::fromDER("\x19\x0");
		$this->assertInstanceOf('ASN1\Type\Primitive\GraphicString', $el);
	}
	
	public function testValue() {
		$str = "Hello World!";
		$el = GraphicString::fromDER("\x19\x0c$str");
		$this->assertEquals($str, $el->str());
	}
}
