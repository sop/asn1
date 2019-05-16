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
        $int->intVal();
    }

    public function testUnderflow()
    {
        $int = new BigInt(gmp_strval(gmp_init(PHP_INT_MIN, 10) - 1));
        $this->expectException(\RuntimeException::class);
        $int->intVal();
    }

    public function testToString()
    {
        $int = new BigInt(1);
        $this->assertSame('1', strval($int));
    }
}
