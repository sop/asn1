<?php

declare(strict_types=1);

use ASN1\Type\Primitive\NullType;

/**
 * @group decode
 * @group null
 */
class NullDecodeTest extends PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $el = NullType::fromDER("\x5\0");
        $this->assertInstanceOf(NullType::class, $el);
    }
    
    /**
     * @expectedException ASN1\Exception\DecodeException
     */
    public function testInvalidLength()
    {
        NullType::fromDER("\x5\x1\x0");
    }
    
    /**
     * @expectedException ASN1\Exception\DecodeException
     */
    public function testNotPrimitive()
    {
        NullType::fromDER("\x25\x0");
    }
}
