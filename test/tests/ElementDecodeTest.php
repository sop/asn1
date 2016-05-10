<?php

use ASN1\Element;
use ASN1\Type\Primitive\Boolean;
use ASN1\Type\Primitive\NullType;


/**
 * @group decode
 * @group element
 */
class ElementDecodeTest extends PHPUnit_Framework_TestCase
{
	public function testAbstract() {
		$el = Element::fromDER("\x5\x0");
		$this->assertInstanceOf(NullType::class, $el);
	}
	
	public function testConcrete() {
		$el = NullType::fromDER("\x5\x0");
		$this->assertInstanceOf(NullType::class, $el);
	}
	
	/**
	 * @expectedException UnexpectedValueException
	 */
	public function testConcreteWrongClass() {
		Boolean::fromDER("\x5\x0");
	}
	
	/**
	 * @expectedException PHPUnit_Framework_Error_Warning
	 * @expectedExceptionMessageRegExp /assert.+?is_string.+?failed/
	 */
	public function testInvalidParam() {
		Element::fromDER(new \stdClass());
	}
	
	/**
	 * @expectedException UnexpectedValueException
	 */
	public function testUnimplementedFail() {
		Element::fromDER("\x1f\x7f\x0");
	}
	
	/**
	 * @expectedException UnexpectedValueException
	 */
	public function testExpectTaggedFail() {
		Element::fromDER("\x5\x0")->expectTagged();
	}
}
