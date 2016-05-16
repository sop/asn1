<?php

use ASN1\Element;
use ASN1\Type\Primitive\GeneralizedTime;
use ASN1\Type\TimeType;


/**
 * @group type
 * @group generalized-time
 */
class GeneralizedTimeTest extends PHPUnit_Framework_TestCase
{
	public function testCreate() {
		$el = GeneralizedTime::fromString("Mon Jan 2 15:04:05 MST 2006");
		$this->assertInstanceOf(GeneralizedTime::class, $el);
		return $el;
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
		$el = GeneralizedTime::fromDER($data);
		$this->assertInstanceOf(GeneralizedTime::class, $el);
		return $el;
	}
	
	/**
	 * @depends testCreate
	 * @depends testDecode
	 *
	 * @param TimeType $ref
	 * @param TimeType $el
	 */
	public function testRecoded(TimeType $ref, TimeType $el) {
		$this->assertEquals($ref->dateTime()
			->getTimestamp(), $el->dateTime()
			->getTimestamp());
	}
}
