<?php

use ASN1\Element;
use ASN1\Type\Primitive\CharacterString;


/**
 * @group type
 * @group character-string
 */
class CharacterStringTest extends PHPUnit_Framework_TestCase
{
	public function testCreate() {
		$el = new CharacterString("");
		$this->assertInstanceOf(CharacterString::class, $el);
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
		$el = CharacterString::fromDER($data);
		$this->assertInstanceOf(CharacterString::class, $el);
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
}
