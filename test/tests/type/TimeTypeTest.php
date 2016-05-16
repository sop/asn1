<?php

use ASN1\Type\Primitive\GeneralizedTime;
use ASN1\Type\TimeType;


/**
 * @group type
 * @group time
 */
class TimeTypeTest extends PHPUnit_Framework_TestCase
{
	public function testFromString() {
		$el = GeneralizedTime::fromString("Mon Jan 2 15:04:05 MST 2006");
		$this->assertInstanceOf(TimeType::class, $el);
	}
	
	public function testFromStringWithTz() {
		$el = GeneralizedTime::fromString("Mon Jan 2 15:04:05 MST 2006", 
			"Europe/Helsinki");
		$this->assertInstanceOf(TimeType::class, $el);
	}
	
	/**
	 * @expectedException RuntimeException
	 */
	public function testFromInvalidStringFail() {
		GeneralizedTime::fromString("fail");
	}
	
	/**
	 * @expectedException RuntimeException
	 */
	public function testFromStringWithInvalidTzFail() {
		GeneralizedTime::fromString("Mon Jan 2 15:04:05 MST 2006", "nope");
	}
}
