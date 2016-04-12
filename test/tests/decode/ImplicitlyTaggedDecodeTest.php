<?php

use ASN1\Element;
use ASN1\Type\TaggedType;
use ASN1\Component\Identifier;


/**
 * @group decode
 */
class ImplicitlyTaggedDecodeTest extends PHPUnit_Framework_TestCase
{
	public function testType() {
		$el = TaggedType::fromDER("\x80\x0");
		$this->assertInstanceOf('ASN1\Type\Tagged\DERTaggedType', $el);
	}
	
	public function testTag() {
		$el = TaggedType::fromDER("\x81\x0");
		$this->assertEquals(1, $el->tag());
	}
	
	public function testTypeClass() {
		$el = TaggedType::fromDER("\x80\x0");
		$this->assertEquals(Identifier::CLASS_CONTEXT_SPECIFIC, 
			$el->typeClass());
	}
	
	public function testInnerType() {
		$el = TaggedType::fromDER("\x80\x0");
		$this->assertEquals(Element::TYPE_NULL, 
			$el->implicit(Element::TYPE_NULL)
				->tag());
	}
	
	public function testInnerClass() {
		$el = TaggedType::fromDER("\x80\x0");
		$this->assertEquals(Identifier::CLASS_UNIVERSAL, 
			$el->implicit(Element::TYPE_NULL)
				->typeClass());
	}
	
	public function testInnerPrimitive() {
		$el = TaggedType::fromDER("\x80\x0");
		$this->assertFalse(
			$el->implicit(Element::TYPE_NULL)
				->isConstructed());
	}
	
	public function testInnerConstructed() {
		$el = TaggedType::fromDER("\xa0\x0");
		$this->assertTrue(
			$el->implicit(Element::TYPE_SEQUENCE)
				->isConstructed());
	}
	
	/**
	 * Test that attempting to decode implicitly tagged sequence that
	 * doesn't have constructed bit set fails.
	 * @expectedException ASN1\Exception\DecodeException
	 */
	public function testInnerConstructedFail() {
		TaggedType::fromDER("\x80\x0")->implicit(Element::TYPE_SEQUENCE);
	}
	
	public function testNested() {
		$el = TaggedType::fromDER("\xa1\x2\x82\x0");
		$this->assertEquals(1, $el->tag());
		$el = $el->implicit(Element::TYPE_SEQUENCE);
		$this->assertEquals(2, $el->at(0)
			->tag());
		$el = $el->at(0)->implicit(Element::TYPE_NULL);
		$this->assertEquals(Element::TYPE_NULL, $el->tag());
	}
}
