<?php

use ASN1\Element;
use ASN1\Type\Primitive\PrintableString;


/**
 * @group type
 * @group printable-string
 */
class PrintableStringTest extends PHPUnit_Framework_TestCase
{
	public function testCreate() {
		$el = new PrintableString("");
		$this->assertInstanceOf(PrintableString::class, $el);
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
		$el = PrintableString::fromDER($data);
		$this->assertInstanceOf(PrintableString::class, $el);
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
