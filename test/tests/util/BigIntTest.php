<?php

declare(strict_types = 1);

use ASN1\Util\BigInt;

/**
 * @group util
 */
class BigIntTest extends PHPUnit_Framework_TestCase
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
    
    /**
     * @expectedException RuntimeException
     */
    public function testOverflow()
    {
        $int = new BigInt(gmp_strval(gmp_init(PHP_INT_MAX, 10) + 1));
        $int->intVal();
    }
    
    /**
     * @expectedException RuntimeException
     */
    public function testUnderflow()
    {
        $int = new BigInt(gmp_strval(gmp_init(PHP_INT_MIN, 10) - 1));
        $int->intVal();
    }
    
    public function testToString()
    {
        $int = new BigInt(1);
        $this->assertSame("1", strval($int));
    }
}
