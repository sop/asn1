<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Component\Length;
use Sop\ASN1\Exception\DecodeException;

/**
 * @group decode
 * @group length
 *
 * @internal
 */
class LengthDecodeTest extends TestCase
{
    public function testType()
    {
        $length = Length::fromDER("\x0");
        $this->assertInstanceOf(Length::class, $length);
    }

    public function testDefinite()
    {
        $length = Length::fromDER("\x00");
        $this->assertFalse($length->isIndefinite());
    }

    public function testIndefinite()
    {
        $length = Length::fromDER("\x80");
        $this->assertTrue($length->isIndefinite());
    }

    public function testLengthFailsBecauseIndefinite()
    {
        $this->expectException(LogicException::class);
        Length::fromDER("\x80")->length();
    }

    public function testIntLengthFailsBecauseIndefinite()
    {
        $this->expectException(LogicException::class);
        Length::fromDER("\x80")->intLength();
    }

    public function testHugeLengthHasNoIntval()
    {
        $der = "\xfe" . str_repeat("\xff", 126);
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Integer overflow.');
        Length::fromDER($der)->intLength();
    }

    public function testShortForm()
    {
        $length = Length::fromDER("\x7f");
        $this->assertEquals(0x7f, $length->length());
        $this->assertEquals(0x7f, $length->intLength());
    }

    public function testLongForm()
    {
        $length = Length::fromDER("\x81\xff");
        $this->assertEquals(0xff, $length->length());
    }

    public function testLongForm2()
    {
        $length = Length::fromDER("\x82\xca\xfe");
        $this->assertEquals(0xcafe, $length->length());
        $this->assertEquals(0xcafe, $length->intLength());
    }

    /**
     * Tests failure when there's too few bytes.
     */
    public function testInvalidLongForm()
    {
        $this->expectException(DecodeException::class);
        Length::fromDER("\x82\xff");
    }

    /**
     * Tests failure when first byte is 0xff.
     */
    public function testInvalidLength()
    {
        $this->expectException(DecodeException::class);
        Length::fromDER("\xff" . str_repeat("\0", 127));
    }

    public function testHugeLength()
    {
        $der = "\xfe" . str_repeat("\xff", 126);
        $length = Length::fromDER($der);
        $num = gmp_init(str_repeat('ff', 126), 16);
        $this->assertEquals($length->length(), gmp_strval($num));
    }

    public function testOffsetFail()
    {
        $offset = 1;
        $this->expectException(DecodeException::class);
        Length::fromDER("\x0", $offset);
    }

    public function testExpectFail()
    {
        $offset = 0;
        $this->expectException(DecodeException::class);
        Length::expectFromDER("\x01", $offset);
    }

    public function testExpectFail2()
    {
        $offset = 0;
        $this->expectException(DecodeException::class);
        Length::expectFromDER("\x01\x00", $offset, 2);
    }

    public function testExpectFailIndefinite()
    {
        $offset = 0;
        $this->expectException(DecodeException::class);
        $this->expectExceptionMessageRegExp('/got indefinite/');
        Length::expectFromDER("\x80", $offset, 1);
    }
}
