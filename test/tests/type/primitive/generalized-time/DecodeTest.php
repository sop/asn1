<?php

use ASN1\Type\Primitive\GeneralizedTime;


/**
 * @group decode
 * @group generalized-time
 */
class GeneralizedTimeDecodeTest extends PHPUnit_Framework_TestCase
{
	public function testType() {
		$el = GeneralizedTime::fromDER("\x18\x15" . "20060102220405.99999Z");
		$this->assertInstanceOf(GeneralizedTime::class, $el);
	}
	
	public function testValue() {
		$date = strtotime("Mon Jan 2 15:04:05 MST 2006");
		$el = GeneralizedTime::fromDER("\x18\x0f" . "20060102220405Z");
		$this->assertEquals($date, $el->dateTime()
			->getTimestamp());
	}
	
	public function testFractions() {
		$ts = strtotime("Mon Jan 2 15:04:05 MST 2006");
		$dt = \DateTimeImmutable::createFromFormat("U.u", "$ts.99999", 
			new \DateTimeZone("UTC"));
		$el = GeneralizedTime::fromDER("\x18\x15" . "20060102220405.99999Z");
		$this->assertEquals($dt->format("c u"), 
			$el->dateTime()
				->format("c u"));
	}
	
	public function testNoFractions() {
		$dt = new \DateTimeImmutable("Mon Jan 2 15:04:05 MST 2006");
		$dt = $dt->setTimezone(new \DateTimeZone("UTC"));
		$el = GeneralizedTime::fromDER("\x18\x0f" . "20060102220405Z");
		$this->assertEquals($dt->format("c u"), 
			$el->dateTime()
				->format("c u"));
	}
	
	/**
	 * @expectedException ASN1\Exception\DecodeException
	 * @expectedExceptionMessage omit trailing zeroes
	 */
	public function testInvalidFractions() {
		GeneralizedTime::fromDER("\x18\x12" . "20060102220405.50Z");
	}
	
	/**
	 * @expectedException ASN1\Exception\DecodeException
	 * @expectedExceptionMessage omit trailing zeroes
	 */
	public function testInvalidFractions2() {
		GeneralizedTime::fromDER("\x18\x11" . "20060102220405.0Z");
	}
	
	/**
	 * @expectedException ASN1\Exception\DecodeException
	 * @expectedExceptionMessage Invalid GeneralizedTime format
	 */
	public function testInvalidFractionsOnlyDot() {
		GeneralizedTime::fromDER("\x18\x10" . "20060102220405.Z");
	}
	
	/**
	 * @expectedException ASN1\Exception\DecodeException
	 * @expectedExceptionMessage Invalid GeneralizedTime format
	 */
	public function testNoTimezone() {
		GeneralizedTime::fromDER("\x18\x0e" . "20060102220405");
	}
	
	/**
	 * @expectedException ASN1\Exception\DecodeException
	 */
	public function testInvalidTime() {
		GeneralizedTime::fromDER("\x18\x19" . "20060102220405.123456789Z");
	}
}
