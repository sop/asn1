<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Exception\DecodeException;
use Sop\ASN1\Type\Primitive\Integer;

/**
 * @group decode
 * @group integer
 *
 * @internal
 */
class IntegerDecodeTest extends TestCase
{
    public function testType()
    {
        $el = Integer::fromDER("\x2\x1\x00");
        $this->assertInstanceOf(Integer::class, $el);
    }

    public function testZero()
    {
        $der = "\x2\x1\x0";
        $this->assertEquals(0, Integer::fromDER($der)->number());
    }

    public function testPositive127()
    {
        $der = "\x2\x1\x7f";
        $this->assertEquals(127, Integer::fromDER($der)->number());
    }

    public function testPositive128()
    {
        $der = "\x2\x2\x0\x80";
        $this->assertEquals(128, Integer::fromDER($der)->number());
    }

    public function testPositive255()
    {
        $der = "\x2\x2\x0\xff";
        $this->assertEquals(255, Integer::fromDER($der)->number());
    }

    public function testPositive256()
    {
        $der = "\x2\x2\x01\x00";
        $this->assertEquals(256, Integer::fromDER($der)->number());
    }

    public function testPositive32767()
    {
        $der = "\x2\x2\x7f\xff";
        $this->assertEquals(32767, Integer::fromDER($der)->number());
    }

    public function testPositive32768()
    {
        $der = "\x2\x3\x0\x80\x00";
        $this->assertEquals(32768, Integer::fromDER($der)->number());
    }

    public function testNegative1()
    {
        $der = "\x2\x1" . chr(0b11111111);
        $this->assertEquals(-1, Integer::fromDER($der)->number());
    }

    public function testNegative2()
    {
        $der = "\x2\x1" . chr(0b11111110);
        $this->assertEquals(-2, Integer::fromDER($der)->number());
    }

    public function testNegative127()
    {
        $der = "\x2\x1" . chr(0b10000001);
        $this->assertEquals(-127, Integer::fromDER($der)->number());
    }

    public function testNegative128()
    {
        $der = "\x2\x1" . chr(0b10000000);
        $this->assertEquals(-128, Integer::fromDER($der)->number());
    }

    public function testNegative129()
    {
        $der = "\x2\x2" . chr(0b11111111) . chr(0b01111111);
        $this->assertEquals(-129, Integer::fromDER($der)->number());
    }

    public function testNegative255()
    {
        $der = "\x2\x2" . chr(0b11111111) . chr(0b00000001);
        $this->assertEquals(-255, Integer::fromDER($der)->number());
    }

    public function testNegative256()
    {
        $der = "\x2\x2" . chr(0b11111111) . chr(0b00000000);
        $this->assertEquals(-256, Integer::fromDER($der)->number());
    }

    public function testNegative257()
    {
        $der = "\x2\x2" . chr(0b11111110) . chr(0b11111111);
        $this->assertEquals(-257, Integer::fromDER($der)->number());
    }

    public function testNegative32767()
    {
        $der = "\x2\x2" . chr(0b10000000) . chr(0b00000001);
        $this->assertEquals(-32767, Integer::fromDER($der)->number());
    }

    public function testNegative32768()
    {
        $der = "\x2\x2" . chr(0b10000000) . chr(0b00000000);
        $this->assertEquals(-32768, Integer::fromDER($der)->number());
    }

    public function testNegative32769()
    {
        $der = "\x2\x3" . chr(0b11111111) . chr(0b01111111) . chr(0b11111111);
        $this->assertEquals(-32769, Integer::fromDER($der)->number());
    }

    public function testNegative65535()
    {
        $der = "\x2\x3" . chr(0b11111111) . chr(0b00000000) . chr(0b00000001);
        $this->assertEquals(-65535, Integer::fromDER($der)->number());
    }

    public function testNegative65536()
    {
        $der = "\x2\x3" . chr(0b11111111) . chr(0b00000000) . chr(0b00000000);
        $this->assertEquals(-65536, Integer::fromDER($der)->number());
    }

    public function testNegative65537()
    {
        $der = "\x2\x3" . chr(0b11111110) . chr(0b11111111) . chr(0b11111111);
        $this->assertEquals(-65537, Integer::fromDER($der)->number());
    }

    public function testInvalidLength()
    {
        $der = "\x2\x2\x0";
        $this->expectException(DecodeException::class);
        Integer::fromDER($der);
    }

    public function testHugePositive()
    {
        $der = "\x2\x82\xff\xff\x7f" . str_repeat("\xff", 0xfffe);
        $num = gmp_init('7f' . str_repeat('ff', 0xfffe), 16);
        $this->assertEquals(gmp_strval($num), Integer::fromDER($der)->number());
    }

    public function testHugeNegative()
    {
        $der = "\x2\x82\xff\xff\x80" . str_repeat("\x00", 0xfffe);
        $num = 0 - gmp_init('80' . str_repeat('00', 0xfffe), 16);
        $this->assertEquals(gmp_strval($num), Integer::fromDER($der)->number());
    }
}
