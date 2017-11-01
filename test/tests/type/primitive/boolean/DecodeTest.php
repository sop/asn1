<?php

declare(strict_types=1);

use ASN1\Type\Primitive\Boolean;

/**
 * @group decode
 * @group boolean
 */
class BooleanDecodeTest extends PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $el = Boolean::fromDER("\x1\x1\x00");
        $this->assertInstanceOf(Boolean::class, $el);
    }
    
    public function testTrue()
    {
        $el = Boolean::fromDER("\x1\x1\xff");
        $this->assertTrue($el->value());
    }
    
    public function testFalse()
    {
        $el = Boolean::fromDER("\x1\x1\x00");
        $this->assertFalse($el->value());
    }
    
    /**
     * @expectedException ASN1\Exception\DecodeException
     */
    public function testInvalidDER()
    {
        Boolean::fromDER("\x1\x1\x55");
    }
    
    /**
     * @expectedException ASN1\Exception\DecodeException
     */
    public function testInvalidLength()
    {
        Boolean::fromDER("\x1\x2\x00\x00");
    }
}
