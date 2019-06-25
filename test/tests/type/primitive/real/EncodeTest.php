<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Type\Primitive\Real;

/**
 * @group type
 * @group real
 *
 * @internal
 */
class RealEncodeTest extends TestCase
{
    public function testLongExponent()
    {
        $real = new Real(1, gmp_init('0x40000000'), 2);
        $this->assertEquals(hex2bin('090783044000000001'), $real->toDER());
    }

    public function testInvalidSpecial()
    {
        $real = new Real(0, Real::INF_EXPONENT, 10);
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Invalid special value');
        $real->toDER();
    }

    public function testMantissaNormalization()
    {
        $real = new Real(8, 0, 2);
        $this->assertEquals(hex2bin('0903800301'), $real->toDER());
        $this->assertEquals(8.0, Real::fromDER($real->toDER())->floatVal());
    }

    public function testMantissaNormalizationBase8()
    {
        $real = (new Real(8, 3, 2))->withStrictDER(false);
        $this->assertEquals(hex2bin('0903900201'), $real->toDER());
        $this->assertEquals(64.0, Real::fromDER($real->toDER())->floatVal());
    }

    public function testMantissaNormalizationBase16()
    {
        $real = (new Real(16, 4, 2))->withStrictDER(false);
        $this->assertEquals(hex2bin('0903A00201'), $real->toDER());
        $this->assertEquals(256.0, Real::fromDER($real->toDER())->floatVal());
    }

    public function testScaleFactor()
    {
        $real = (new Real(128, 4, 2))->withStrictDER(false);
        $this->assertEquals(hex2bin('0903AC0201'), $real->toDER());
        $this->assertEquals(2048.0, Real::fromDER($real->toDER())->floatVal());
    }

    public function testVeryLongExponent()
    {
        $real = new Real(1, gmp_init('0x40' . str_repeat('00', 254)), 2);
        $expected = hex2bin('0982010283ff40' . str_repeat('00', 254) . '01');
        $this->assertEquals($expected, $real->toDER());
    }

    public function testTooLongExponent()
    {
        $real = new Real(1, gmp_init('0x40' . str_repeat('00', 255)), 2);
        $this->expectException(\RangeException::class);
        $this->expectExceptionMessage('Exponent encoding is too long');
        $real->toDER();
    }
}
