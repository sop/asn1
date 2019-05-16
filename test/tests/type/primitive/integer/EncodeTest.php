<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Type\Primitive\Integer;

/**
 * @group encode
 * @group integer
 *
 * @internal
 */
class IntegerEncodeTest extends TestCase
{
    public function testZero()
    {
        $int = new Integer(0);
        $this->assertEquals("\x2\x1\x0", $int->toDER());
    }

    public function testNegativeZero()
    {
        $int = new Integer('-0');
        $this->assertEquals("\x2\x1\x0", $int->toDER());
    }

    public function testInvalidNumber()
    {
        $this->expectException(\InvalidArgumentException::class);
        new Integer('one');
    }

    public function testEmpty()
    {
        $this->expectException(\InvalidArgumentException::class);
        new Integer('');
    }

    public function testPositive127()
    {
        $int = new Integer(127);
        $this->assertEquals("\x2\x1\x7f", $int->toDER());
    }

    public function testPositive128()
    {
        $int = new Integer(128);
        $this->assertEquals("\x2\x2\x0\x80", $int->toDER());
    }

    public function testPositive255()
    {
        $int = new Integer(255);
        $this->assertEquals("\x2\x2\x0\xff", $int->toDER());
    }

    public function testPositive256()
    {
        $int = new Integer(256);
        $this->assertEquals("\x2\x2\x01\x00", $int->toDER());
    }

    public function testPositive32767()
    {
        $int = new Integer(32767);
        $this->assertEquals("\x2\x2\x7f\xff", $int->toDER());
    }

    public function testPositive32768()
    {
        $int = new Integer(32768);
        $this->assertEquals("\x2\x3\x0\x80\x00", $int->toDER());
    }

    public function testNegative1()
    {
        $int = new Integer(-1);
        $der = "\x2\x1" . chr(0b11111111);
        $this->assertEquals($der, $int->toDER());
    }

    public function testNegative2()
    {
        $int = new Integer(-2);
        $der = "\x2\x1" . chr(0b11111110);
        $this->assertEquals($der, $int->toDER());
    }

    public function testNegative127()
    {
        $int = new Integer(-127);
        $der = "\x2\x1" . chr(0b10000001);
        $this->assertEquals($der, $int->toDER());
    }

    public function testNegative128()
    {
        $int = new Integer(-128);
        $der = "\x2\x1" . chr(0b10000000);
        $this->assertEquals($der, $int->toDER());
    }

    public function testNegative129()
    {
        $int = new Integer(-129);
        $der = "\x2\x2" . chr(0b11111111) . chr(0b01111111);
        $this->assertEquals($der, $int->toDER());
    }

    public function testNegative255()
    {
        $int = new Integer(-255);
        $der = "\x2\x2" . chr(0b11111111) . chr(0b00000001);
        $this->assertEquals($der, $int->toDER());
    }

    public function testNegative256()
    {
        $int = new Integer(-256);
        $der = "\x2\x2" . chr(0b11111111) . chr(0b00000000);
        $this->assertEquals($der, $int->toDER());
    }

    public function testNegative257()
    {
        $int = new Integer(-257);
        $der = "\x2\x2" . chr(0b11111110) . chr(0b11111111);
        $this->assertEquals($der, $int->toDER());
    }

    public function testNegative32767()
    {
        $int = new Integer(-32767);
        $der = "\x2\x2" . chr(0b10000000) . chr(0b00000001);
        $this->assertEquals($der, $int->toDER());
    }

    public function testNegative32768()
    {
        $int = new Integer(-32768);
        $der = "\x2\x2" . chr(0b10000000) . chr(0b00000000);
        $this->assertEquals($der, $int->toDER());
    }

    public function testNegative32769()
    {
        $int = new Integer(-32769);
        $der = "\x2\x3" . chr(0b11111111) . chr(0b01111111) . chr(0b11111111);
        $this->assertEquals($der, $int->toDER());
    }

    public function testNegative65535()
    {
        $int = new Integer(-65535);
        $der = "\x2\x3" . chr(0b11111111) . chr(0b00000000) . chr(0b00000001);
        $this->assertEquals($der, $int->toDER());
    }

    public function testNegative65536()
    {
        $int = new Integer(-65536);
        $der = "\x2\x3" . chr(0b11111111) . chr(0b00000000) . chr(0b00000000);
        $this->assertEquals($der, $int->toDER());
    }

    public function testNegative65537()
    {
        $int = new Integer(-65537);
        $der = "\x2\x3" . chr(0b11111110) . chr(0b11111111) . chr(0b11111111);
        $this->assertEquals($der, $int->toDER());
    }

    public function testHugePositive()
    {
        $num = gmp_init('7f' . str_repeat('ff', 0xfffe), 16);
        $int = new Integer(gmp_strval($num));
        $der = "\x2\x82\xff\xff\x7f" . str_repeat("\xff", 0xfffe);
        $this->assertEquals($der, $int->toDER());
    }

    public function testHugeNegative()
    {
        $num = 0 - gmp_init('80' . str_repeat('00', 0xfffe), 16);
        $int = new Integer(gmp_strval($num));
        $der = "\x2\x82\xff\xff\x80" . str_repeat("\x00", 0xfffe);
        $this->assertEquals($der, $int->toDER());
    }
}
