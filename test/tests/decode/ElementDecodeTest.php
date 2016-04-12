<?php

use ASN1\Element;
use ASN1\Type\Primitive\NullType;
use ASN1\Type\Primitive\Boolean;


/**
 * @group decode
 */
class ElementDecodeTest extends PHPUnit_Framework_TestCase
{
	public function testAbstract() {
		$el = Element::fromDER("\x5\x0");
		$this->assertInstanceOf('ASN1\Type\Primitive\NullType', $el);
	}
	
	public function testConcrete() {
		$el = NullType::fromDER("\x5\x0");
		$this->assertInstanceOf('ASN1\Type\Primitive\NullType', $el);
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
}
