<?php

declare(strict_types=1);

use ASN1\Type\Primitive\BitString;

/**
 * @group decode
 * @group bit-string
 */
class BitStringDecodeTest extends PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $el = BitString::fromDER("\x3\x2\x0\xff");
        $this->assertInstanceOf(BitString::class, $el);
    }
    
    public function testUnusedBits()
    {
        $el = BitString::fromDER("\x3\x3\x4\xff\xf0");
        $this->assertEquals(4, $el->unusedBits());
    }
    
    public function testNumBits()
    {
        $el = BitString::fromDER("\x3\x3\x4\xff\xf0");
        $this->assertEquals(12, $el->numBits());
    }
    
    /**
     * @expectedException ASN1\Exception\DecodeException
     *
     * Test that exception is thrown if unused bits are not zero
     */
    public function testDerPadding()
    {
        BitString::fromDER("\x3\x3\x4\xff\xf8");
    }
    
    public function testSetBit()
    {
        $el = BitString::fromDER("\x3\x3\x4\x08\x00");
        $this->assertTrue($el->testBit(4));
    }
    
    public function testUnsetBit()
    {
        $el = BitString::fromDER("\x3\x3\x4\x08\x00");
        $this->assertFalse($el->testBit(5));
    }
    
    /**
     * @expectedException OutOfBoundsException
     * @expectedExceptionMessage unused bit
     *
     * Test that testing unused bit throws an exception
     */
    public function testBitFail()
    {
        $el = BitString::fromDER("\x3\x3\x4\x08\x00");
        $el->testBit(12);
    }
    
    /**
     * @expectedException OutOfBoundsException
     * @expectedExceptionMessage out of bounds
     *
     * Test that testing out of bounds throws an exception
     */
    public function testBitFail2()
    {
        $el = BitString::fromDER("\x3\x3\x4\x08\x00");
        $el->testBit(16);
    }
    
    /**
     * @expectedException ASN1\Exception\DecodeException
     */
    public function testLengthFail()
    {
        BitString::fromDER("\x3\x0");
    }
    
    /**
     * @expectedException ASN1\Exception\DecodeException
     */
    public function testUnusedBitsFail()
    {
        BitString::fromDER("\x3\x3\x8\xff\x00");
    }
}
