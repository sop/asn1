<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Component\Length;

/**
 * @group encode
 * @group length
 *
 * @internal
 */
class LengthEncodeTest extends TestCase
{
    public function testDefinite()
    {
        $length = new Length(0, false);
        $this->assertEquals("\x0", $length->toDER());
    }

    public function testIndefinite()
    {
        $length = new Length(0, true);
        $this->assertEquals("\x80", $length->toDER());
    }

    public function testShort()
    {
        $length = new Length(0x7f);
        $this->assertEquals("\x7f", $length->toDER());
    }

    public function testLong()
    {
        $length = new Length(0xff);
        $this->assertEquals("\x81\xff", $length->toDER());
    }

    public function testLong2()
    {
        $length = new Length(0xcafe);
        $this->assertEquals("\x82\xca\xfe", $length->toDER());
    }

    public function testHugeLength()
    {
        $largenum = gmp_init(str_repeat('ff', 126), 16);
        $length = new Length(gmp_strval($largenum, 10));
        $expected = "\xfe" . str_repeat("\xff", 126);
        $this->assertEquals($expected, $length->toDER());
    }

    public function testTooLong()
    {
        $largenum = gmp_init(str_repeat('ff', 127), 16);
        $length = new Length(gmp_strval($largenum, 10));
        $this->expectException(LogicException::class);
        $length->toDER();
    }

    public function testTooLong2()
    {
        $largenum = gmp_init(str_repeat('ff', 128), 16);
        $length = new Length(gmp_strval($largenum, 10));
        $this->expectException(LogicException::class);
        $length->toDER();
    }
}
