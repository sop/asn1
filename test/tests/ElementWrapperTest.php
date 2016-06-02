<?php

use ASN1\DERData;
use ASN1\ElementWrapper;
use ASN1\Feature\ElementBase;
use ASN1\Type\Primitive\NullType;


class ElementWrapperTest extends PHPUnit_Framework_TestCase
{
	public function testAsElement() {
		$wrap = new ElementWrapper(new NullType());
		$this->assertInstanceOf(ElementBase::class, $wrap->asElement());
	}
	
	/**
	 * @expectedException UnexpectedValueException
	 */
	public function testAsTaggedFail() {
		$wrap = new ElementWrapper(new NullType());
		$wrap->asTagged();
	}
	
	/**
	 * @expectedException UnexpectedValueException
	 */
	public function testAsStringFail() {
		$wrap = new ElementWrapper(new NullType());
		$wrap->asString();
	}
	
	/**
	 * @expectedException UnexpectedValueException
	 */
	public function testAsTimeFail() {
		$wrap = new ElementWrapper(new NullType());
		$wrap->asTime();
	}
	
	/**
	 * @expectedException UnexpectedValueException
	 */
	public function testPrivateTypeFail() {
		$el = new DERData("\xdf\x7f\x0");
		$wrap = new ElementWrapper($el);
		$wrap->asNull();
	}
}
