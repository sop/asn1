<?php

use ASN1\Element;
use ASN1\ElementWrapper;
use ASN1\Type\Primitive\NullType;
use ASN1\Type\Primitive\ObjectIdentifier;


/**
 * @group type
 * @group oid
 */
class ObjectIdentifierTest extends PHPUnit_Framework_TestCase
{
	public function testCreate() {
		$el = new ObjectIdentifier("1.3.6.1.3");
		$this->assertInstanceOf(ObjectIdentifier::class, $el);
		return $el;
	}
	
	/**
	 * @depends testCreate
	 *
	 * @param Element $el
	 */
	public function testTag(Element $el) {
		$this->assertEquals(Element::TYPE_OBJECT_IDENTIFIER, $el->tag());
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
		$el = ObjectIdentifier::fromDER($data);
		$this->assertInstanceOf(ObjectIdentifier::class, $el);
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
		$this->assertInstanceOf(ObjectIdentifier::class, 
			$wrap->asObjectIdentifier());
	}
	
	/**
	 * @expectedException UnexpectedValueException
	 */
	public function testWrappedFail() {
		$wrap = new ElementWrapper(new NullType());
		$wrap->asObjectIdentifier();
	}
}
