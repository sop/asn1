<?php

use ASN1\Component\Length;


/**
 * @group decode
 */
class LengthDecodeTest extends PHPUnit_Framework_TestCase
{
	public function testType() {
		$length = Length::fromDER("\x0");
		$this->assertInstanceOf('ASN1\Component\Length', $length);
	}
	
	public function testDefinite() {
		$length = Length::fromDER("\x00");
		$this->assertFalse($length->isIndefinite());
	}
	
	public function testIndefinite() {
		$length = Length::fromDER("\x80");
		$this->assertTrue($length->isIndefinite());
	}
	
	public function testShortForm() {
		$length = Length::fromDER("\x7f");
		$this->assertEquals(0x7f, $length->length());
	}
	
	public function testLongForm() {
		$length = Length::fromDER("\x81\xff");
		$this->assertEquals(0xff, $length->length());
	}
	
	public function testLongForm2() {
		$length = Length::fromDER("\x82\xca\xfe");
		$this->assertEquals(0xcafe, $length->length());
	}
	
	/**
	 * Tests failure when there's too few bytes
	 *
	 * @expectedException ASN1\Exception\DecodeException
	 */
	public function testInvalidLongForm() {
		Length::fromDER("\x82\xff");
	}
	
	/**
	 * Tests failure when first byte is 0xff
	 *
	 * @expectedException ASN1\Exception\DecodeException
	 */
	public function testInvalidLength() {
		Length::fromDER("\xff");
	}
	
	public function testHugeLength() {
		$der = "\xfe" . str_repeat("\xff", 126);
		$length = Length::fromDER($der);
		$num = gmp_init(str_repeat("ff", 126), 16);
		$this->assertEquals($length->length(), gmp_strval($num));
	}
	
	/**
	 * @expectedException ASN1\Exception\DecodeException
	 */
	public function testExpectFail() {
		$offset = 0;
		Length::expectFromDER("\x01", $offset);
	}
	
	/**
	 * @expectedException ASN1\Exception\DecodeException
	 */
	public function testExpectFail2() {
		$offset = 0;
		Length::expectFromDER("\x01\x00", $offset, 2);
	}
	
	/**
	 * @expectedException ASN1\Exception\DecodeException
	 */
	public function testIndefiniteDER() {
		$offset = 0;
		Length::expectFromDER("\x80", $offset);
	}
	
	/**
	 * @expectedException PHPUnit_Framework_Error_Warning
	 * @expectedExceptionMessageRegExp /assert.+?is_string.+?failed/
	 */
	public function testInvalidParam() {
		Length::fromDER(new \stdClass());
	}
}
