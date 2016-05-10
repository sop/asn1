<?php

use ASN1\Component\Identifier;
use ASN1\Element;
use ASN1\Type\Primitive\NullType;
use ASN1\Type\Tagged\ImplicitlyTaggedType;
use ASN1\Type\Tagged\ImplicitTagging;


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
}
