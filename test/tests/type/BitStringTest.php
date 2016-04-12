<?php

use ASN1\Type\Primitive\BitString;


/**
 * @group type
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
			[0, 8, 0xff],
			[1, 2, 0x03],
			[6, 2, 0x03],
			[2, 4, 0x0f]
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
			[0, 8, 0xff],
			[6, 4, 0x0f],
			[12, 4, 0x0f]
		);
	}
	
	/**
	 * @expectedException \OutOfBoundsException
	 */
	public function testRangeOOB() {
		$bs = new BitString("\xff");
		$bs->range(7, 2);
	}
}
