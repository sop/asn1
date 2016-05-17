<?php

use ASN1\Element;
use ASN1\Type\Primitive\UTCTime;
use ASN1\Type\TimeType;


/**
 * @group type
 * @group utc-time
 */
class UTCTimeTest extends PHPUnit_Framework_TestCase
{
	public function testCreate() {
		$el = UTCTime::fromString("Mon Jan 2 15:04:05 MST 2006");
		$this->assertInstanceOf(UTCTime::class, $el);
		return $el;
	}
	
	/**
	 * @depends testCreate
	 *
	 * @param Element $el
	 */
	public function testTag(Element $el) {
		$this->assertEquals(Element::TYPE_UTC_TIME, $el->tag());
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
		$el = UTCTime::fromDER($data);
		$this->assertInstanceOf(UTCTime::class, $el);
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
