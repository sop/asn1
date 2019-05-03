<?php
declare(strict_types = 1);

use ASN1\Type\Primitive\EOC;

/**
 *
 * @group decode
 * @group eoc
 */
class EOCDecodeTest extends PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $el = EOC::fromDER("\0\0");
        $this->assertInstanceOf(EOC::class, $el);
    }
    
    /**
     *
     * @expectedException ASN1\Exception\DecodeException
     */
    public function testInvalidLength()
    {
        EOC::fromDER("\x0\x1\x0");
    }
    
    /**
     *
     * @expectedException ASN1\Exception\DecodeException
     */
    public function testNotPrimitive()
    {
        EOC::fromDER("\x20\x0");
    }
}
