<?php

use ASN1\Element;
use ASN1\Type\Primitive\NullType;


/**
 * @group element
 */
class ElementTest extends PHPUnit_Framework_TestCase
{
	public function testUnknownTagToName() {
		$this->assertEquals("TAG 100", Element::tagToName(100));
	}
	
	public function testIsTypeUniversalInvalidClass() {
		$el = new NullType();
		$cls = new ReflectionClass($el);
		$prop = $cls->getProperty("_typeTag");
		$prop->setAccessible(true);
		$prop->setValue($el, Element::TYPE_BOOLEAN);
		$this->assertFalse($el->isType(Element::TYPE_BOOLEAN));
	}
	
	public function testIsPseudotypeFail() {
		$el = new NullType();
		$this->assertFalse($el->isType(-3));
	}
	
	public function testAsElement() {
		$el = new NullType();
		$this->assertEquals($el, $el->asElement());
	}
}
