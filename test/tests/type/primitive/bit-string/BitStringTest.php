<?php

use ASN1\Type\Primitive\BitString;


/**
 * @group type
 * @group bit-string
 */
class BitStringTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @dataProvider ffProvider
	 */
	public function testRange8($start, $length, $result) {
		$bs = new BitString("\xff");
		$this->assertEquals($result, $bs->range($start, $length));
	}
	
	public function ffProvider() {
		return array(
			/* @formatter:off */
			[0, 8, 0xff],
			[1, 2, 0x03],
			[6, 2, 0x03],
			[2, 4, 0x0f]
			/* @formatter:on */
		);
	}
	
	/**
	 * @dataProvider ffffProvider
	 */
	public function testRange16($start, $length, $result) {
		$bs = new BitString("\xff\xff");
		$this->assertEquals($result, $bs->range($start, $length));
	}
	
	public function ffffProvider() {
		return array(
			/* @formatter:off */
			[0, 8, 0xff],
			[6, 4, 0x0f],
			[12, 4, 0x0f]
			/* @formatter:on */
		);
	}
	
	public function testEmptyRange() {
		$bs = new BitString("\0");
		$this->assertEquals(0, $bs->range(0, 0));
	}
	
	/**
	 * @expectedException \OutOfBoundsException
	 */
	public function testRangeOOB() {
		$bs = new BitString("\xff");
		$bs->range(7, 2);
	}
}
