<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Exception\DecodeException;
use Sop\ASN1\Type\Primitive\BitString;

/**
 * @group decode
 * @group bit-string
 *
 * @internal
 */
class BitStringDecodeTest extends TestCase
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
     * Test that exception is thrown if unused bits are not zero.
     */
    public function testDerPadding()
    {
        $this->expectException(DecodeException::class);
        $this->expectExceptionMessage(
            'DER encoded bit string must have zero padding');
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
     * Test that testing unused bit throws an exception.
     */
    public function testBitFail()
    {
        $el = BitString::fromDER("\x3\x3\x4\x08\x00");
        $this->expectException(\OutOfBoundsException::class);
        $this->expectExceptionMessage('unused bit');
        $el->testBit(12);
    }

    /**
     * Test that testing out of bounds throws an exception.
     */
    public function testBitFail2()
    {
        $el = BitString::fromDER("\x3\x3\x4\x08\x00");
        $this->expectException(\OutOfBoundsException::class);
        $this->expectExceptionMessage('out of bounds');
        $el->testBit(16);
    }

    public function testLengthFail()
    {
        $this->expectException(DecodeException::class);
        $this->expectExceptionMessage('Bit string length must be at least 1');
        BitString::fromDER("\x3\x0");
    }

    public function testUnusedBitsFail()
    {
        $this->expectException(DecodeException::class);
        $this->expectExceptionMessage(
            'Unused bits in a bit string must be less than 8');
        BitString::fromDER("\x3\x3\x8\xff\x00");
    }
}
