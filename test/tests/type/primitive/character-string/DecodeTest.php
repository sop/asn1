<?php

use ASN1\Type\Primitive\CharacterString;


/**
 * @group decode
 * @group character-string
 */
class CharacterStringDecodeTest extends PHPUnit_Framework_TestCase
{
	public function testType() {
		$el = CharacterString::fromDER("\x1d\x0");
		$this->assertInstanceOf(CharacterString::class, $el);
	}
	
	public function testValue() {
		$str = "Hello World!";
		$el = CharacterString::fromDER("\x1d\x0c$str");
		$this->assertEquals($str, $el->string());
	}
}
