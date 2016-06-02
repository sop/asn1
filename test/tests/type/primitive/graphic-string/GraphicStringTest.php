<?php

use ASN1\Element;
use ASN1\ElementWrapper;
use ASN1\Type\Primitive\GraphicString;
use ASN1\Type\Primitive\NullType;


/**
 * @group type
 * @group graphic-string
 */
class GraphicStringTest extends PHPUnit_Framework_TestCase
{
	public function testCreate() {
		$el = new GraphicString("");
		$this->assertInstanceOf(GraphicString::class, $el);
		return $el;
	}
	
	/**
	 * @depends testCreate
	 *
	 * @param Element $el
	 */
	public function testTag(Element $el) {
		$this->assertEquals(Element::TYPE_GRAPHIC_STRING, $el->tag());
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
		$el = GraphicString::fromDER($data);
		$this->assertInstanceOf(GraphicString::class, $el);
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
	
	/**
	 * @depends testCreate
	 *
	 * @param Element $el
	 */
	public function testWrapped(Element $el) {
		$wrap = new ElementWrapper($el);
		$this->assertInstanceOf(GraphicString::class, $wrap->asGraphicString());
	}
	
	/**
	 * @expectedException UnexpectedValueException
	 */
	public function testWrappedFail() {
		$wrap = new ElementWrapper(new NullType());
		$wrap->asGraphicString();
	}
}
