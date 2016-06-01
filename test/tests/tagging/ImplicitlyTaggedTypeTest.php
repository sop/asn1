<?php

use ASN1\Component\Identifier;
use ASN1\Element;
use ASN1\Type\Primitive\NullType;
use ASN1\Type\Tagged\ImplicitlyTaggedType;
use ASN1\Type\Tagged\ImplicitTagging;
use ASN1\Type\TaggedType;


/**
 * @group tagging
 * @group implicit-tag
 */
class ImplicitlyTaggedTypeTest extends PHPUnit_Framework_TestCase
{
	public function testCreate() {
		$el = new ImplicitlyTaggedType(1, new NullType());
		$this->assertInstanceOf(ImplicitTagging::class, $el);
		return $el;
	}
	
	/**
	 * @depends testCreate
	 *
	 * @param ImplicitTagging $el
	 */
	public function testGetImplicit(ImplicitTagging $el) {
		$this->assertEquals(Element::TYPE_NULL, 
			$el->implicit(Element::TYPE_NULL)
				->tag());
	}
	
	/**
	 * @depends testCreate
	 * @expectedException UnexpectedValueException
	 *
	 * @param ImplicitTagging $el
	 */
	public function testExpectationFail(ImplicitTagging $el) {
		$el->implicit(Element::TYPE_NULL, Identifier::CLASS_PRIVATE);
	}
	
	/**
	 * @depends testCreate
	 *
	 * @param TaggedType $el
	 */
	public function testExpectImplicit(TaggedType $el) {
		$this->assertInstanceOf(ImplicitTagging::class, $el->expectImplicit());
	}
	
	/**
	 * @depends testCreate
	 * @expectedException UnexpectedValueException
	 *
	 * @param TaggedType $el
	 */
	public function testExpectExplicitFail(TaggedType $el) {
		$el->expectExplicit();
	}
	
	/**
	 * @depends testCreate
	 *
	 * @param TaggedType $el
	 */
	public function testExpectImplicitWithTag(TaggedType $el) {
		$this->assertInstanceOf(ImplicitTagging::class, $el->expectImplicit(1));
	}
	
	/**
	 * @depends testCreate
	 * @expectedException UnexpectedValueException
	 *
	 * @param TaggedType $el
	 */
	public function testExpectImplicitWithInvalidTagFail(TaggedType $el) {
		$el->expectImplicit(2);
	}
	
	/**
	 * @depends testCreate
	 * @expectedException UnexpectedValueException
	 *
	 * @param TaggedType $el
	 */
	public function testExpectTypeFails(TaggedType $el) {
		$el->expectType(1);
	}
}
