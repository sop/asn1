<?php

use ASN1\Element;
use ASN1\Type\Primitive\Integer;


/**
 * @group type
 * @group integer
 */
class IntegerTest extends PHPUnit_Framework_TestCase
{
	public function testCreate() {
		$el = new Integer(1);
		$this->assertInstanceOf(Integer::class, $el);
		return $el;
	}
	
/**
	 * @depends testCreate
	 * 
	 * @param Element $el
	 */
	public function testTag(Element $el) {
		$this->assertEquals(Element::TYPE_INTEGER, $el->tag());
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
		$el = Integer::fromDER($data);
		$this->assertInstanceOf(Integer::class, $el);
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
