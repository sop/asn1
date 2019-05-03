<?php
declare(strict_types = 1);

use ASN1\Component\Length;

/**
 *
 * @group decode
 * @group length
 */
class LengthDecodeTest extends PHPUnit_Framework_TestCase
{
    /**
     */
    public function testType()
    {
        $length = Length::fromDER("\x0");
        $this->assertInstanceOf(Length::class, $length);
    }
    
    /**
     */
    public function testDefinite()
    {
        $length = Length::fromDER("\x00");
        $this->assertFalse($length->isIndefinite());
    }
    
    /**
     */
    public function testIndefinite()
    {
        $length = Length::fromDER("\x80");
        $this->assertTrue($length->isIndefinite());
    }
    
    /**
     *
     * @expectedException LogicException
     */
    public function testLengthFailsBecauseIndefinite()
    {
        Length::fromDER("\x80")->length();
    }
    
    /**
     *
     * @expectedException LogicException
     */
    public function testIntLengthFailsBecauseIndefinite()
    {
        Length::fromDER("\x80")->intLength();
    }
    
    /**
     *
     * @expectedException RuntimeException
     * @expectedExceptionMessage Integer overflow.
     */
    public function testHugeLengthHasNoIntval()
    {
        $der = "\xfe" . str_repeat("\xff", 126);
        Length::fromDER($der)->intLength();
    }
    
    /**
     */
    public function testShortForm()
    {
        $length = Length::fromDER("\x7f");
        $this->assertEquals(0x7f, $length->length());
        $this->assertEquals(0x7f, $length->intLength());
    }
    
    /**
     */
    public function testLongForm()
    {
        $length = Length::fromDER("\x81\xff");
        $this->assertEquals(0xff, $length->length());
    }
    
    /**
     */
    public function testLongForm2()
    {
        $length = Length::fromDER("\x82\xca\xfe");
        $this->assertEquals(0xcafe, $length->length());
        $this->assertEquals(0xcafe, $length->intLength());
    }
    
    /**
     * Tests failure when there's too few bytes
     *
     * @expectedException ASN1\Exception\DecodeException
     */
    public function testInvalidLongForm()
    {
        Length::fromDER("\x82\xff");
    }
    
    /**
     * Tests failure when first byte is 0xff
     *
     * @expectedException ASN1\Exception\DecodeException
     */
    public function testInvalidLength()
    {
        Length::fromDER("\xff" . str_repeat("\0", 127));
    }
    public function testHugeLength()
    {
        $der = "\xfe" . str_repeat("\xff", 126);
        $length = Length::fromDER($der);
        $num = gmp_init(str_repeat("ff", 126), 16);
        $this->assertEquals($length->length(), gmp_strval($num));
    }
    
    /**
     *
     * @expectedException ASN1\Exception\DecodeException
     */
    public function testOffsetFail()
    {
        $offset = 1;
        Length::fromDER("\x0", $offset);
    }
    
    /**
     *
     * @expectedException ASN1\Exception\DecodeException
     */
    public function testExpectFail()
    {
        $offset = 0;
        Length::expectFromDER("\x01", $offset);
    }
    
    /**
     *
     * @expectedException ASN1\Exception\DecodeException
     */
    public function testExpectFail2()
    {
        $offset = 0;
        Length::expectFromDER("\x01\x00", $offset, 2);
    }
    
    /**
     *
     * @expectedException ASN1\Exception\DecodeException
     * @expectedExceptionMessageRegExp /got indefinite/
     */
    public function testExpectFailIndefinite()
    {
        $offset = 0;
        Length::expectFromDER("\x80", $offset, 1);
    }
    
    /**
     *
     * @expectedException \TypeError
     * @expectedExceptionMessageRegExp /must be of the type string/
     */
    public function testInvalidParam()
    {
        Length::fromDER(new \stdClass());
    }
}
