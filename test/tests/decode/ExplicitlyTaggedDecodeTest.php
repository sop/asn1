<?php

use ASN1\Element;
use ASN1\Type\TaggedType;
use ASN1\Component\Identifier;


/**
 * @group decode
 */
class ExplicitlyTaggedDecodeTest extends PHPUnit_Framework_TestCase
{
	public function testType() {
		$el = TaggedType::fromDER("\xa0\x2\x5\x0");
		$this->assertInstanceOf('ASN1\Type\Tagged\DERTaggedType', $el);
	}
	
	public function testTag() {
		$el = TaggedType::fromDER("\xa1\x2\x5\x0");
		$this->assertEquals(1, $el->tag());
	}
	
	public function testTypeClass() {
		$el = TaggedType::fromDER("\xa0\x2\x5\x0");
		$this->assertEquals(Identifier::CLASS_CONTEXT_SPECIFIC, 
			$el->typeClass());
	}
	
	public function testConstructed() {
		$el = TaggedType::fromDER("\xa0\x2\x5\x0");
		$this->assertTrue($el->isConstructed());
	}
	
	public function testInnerType() {
		$el = TaggedType::fromDER("\xa0\x2\x5\x0");
		$this->assertEquals(Element::TYPE_NULL, $el->explicit()
			->tag());
	}
	
	public function testInnerTypeExpected() {
		$el = TaggedType::fromDER("\xa0\x2\x5\x0");
		$this->assertEquals(Element::TYPE_NULL, 
			$el->explicit(Element::TYPE_NULL)
				->tag());
	}
	
	/**
	 * @expectedException \UnexpectedValueException
	 */
	public function testInnerTypeExpectationFail() {
		$el = TaggedType::fromDER("\xa0\x2\x5\x0");
		$el->explicit(Element::TYPE_BOOLEAN);
	}
	
	public function testNestedTagging() {
		$el = TaggedType::fromDER("\xa1\x4\xa2\x2\x5\x0");
		$this->assertEquals(1, $el->tag());
		$this->assertEquals(2, $el->explicit()
			->tag());
		$this->assertEquals(Element::TYPE_NULL, 
			$el->explicit()
				->explicit()
				->tag());
	}
}
