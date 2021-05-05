<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Util\BigInt;

/**
 * @group util
 *
 * @internal
 */
class BigIntTest extends TestCase
{
    public function testMaxInt()
    {
        $int = new BigInt(gmp_strval(gmp_init(PHP_INT_MAX, 10)));
        $this->assertEquals(PHP_INT_MAX, $int->intVal());
    }

    public function testMinInt()
    {
        $int = new BigInt(gmp_strval(gmp_init(PHP_INT_MIN, 10)));
        $this->assertEquals(PHP_INT_MIN, $int->intVal());
    }

    public function testOverflow()
    {
        $int = new BigInt(gmp_strval(gmp_init(PHP_INT_MAX, 10) + 1));
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Integer overflow');
        $int->intVal();
    }

    public function testUnderflow()
    {
        $int = new BigInt(gmp_strval(gmp_init(PHP_INT_MIN, 10) - 1));
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Integer underflow');
        $int->intVal();
    }

    public function testToString()
    {
        $int = new BigInt(1);
        $this->assertSame('1', strval($int));
    }

    public function testGmpObj()
    {
        $int = new BigInt(1);
        $this->assertInstanceOf(\GMP::class, $int->gmpObj());
    }

    /**
     * @requires PHP < 8.0
     */
    public function testInvalidNumberPrePHP8()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unable to convert');
        new BigInt('fail');
    }

    /**
     * @requires PHP >= 8.0
     */
    public function testInvalidNumberPHP8()
    {
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('not an integer');
        new BigInt('fail');
    }

    public function testFromUnsignedOctets()
    {
        $int = BigInt::fromUnsignedOctets(hex2bin('ff'));
        $this->assertEquals(255, $int->intVal());
    }

    public function testFromUnsignedOctetsEmpty()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Empty octets');
        BigInt::fromUnsignedOctets('');
    }

    public function testFromSignedOctets()
    {
        $int = BigInt::fromSignedOctets(hex2bin('80'));
        $this->assertEquals(-128, $int->intVal());
    }

    public function testFromSignedOctetsEmpty()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Empty octets');
        BigInt::fromSignedOctets('');
    }

    public function testToUnsignedOctets()
    {
        $int = new BigInt(255);
        $this->assertEquals(hex2bin('ff'), $int->unsignedOctets());
    }

    public function testToSignedPositiveOctets()
    {
        $int = new BigInt(127);
        $this->assertEquals(hex2bin('7f'), $int->signedOctets());
    }

    public function testToSignedPositiveOctetsPrepend()
    {
        $int = new BigInt(128);
        $this->assertEquals(hex2bin('0080'), $int->signedOctets());
    }

    public function testToSignedNegativeOctets()
    {
        $int = new BigInt(-128);
        $this->assertEquals(hex2bin('80'), $int->signedOctets());
    }

    public function testToSignedNegativeOctetsPrepend()
    {
        $int = new BigInt(-32769);
        $this->assertEquals(hex2bin('ff7fff'), $int->signedOctets());
    }

    public function testToSignedZeroOctets()
    {
        $int = new BigInt(0);
        $this->assertEquals(hex2bin('00'), $int->signedOctets());
    }
}
