<?php

use ASN1\Type\Primitive\BMPString;

/**
 * @group decode
 * @group bmp-string
 */
class BMPStringDecodeTest extends PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $el = BMPString::fromDER("\x1e\x0");
        $this->assertInstanceOf(BMPString::class, $el);
    }
    
    public function testValue()
    {
        $str = "\0H\0e\0l\0l\0o\0 \0W\0o\0r\0l\0d\0!";
        $el = BMPString::fromDER("\x1e\x18$str");
        $this->assertEquals($str, $el->string());
    }
    
    /**
     * @expectedException ASN1\Exception\DecodeException
     */
    public function testInvalidValue()
    {
        // last character is not 2 octets
        $str = "\0H\0e\0l\0l\0o\0 \0W\0o\0r\0l\0d!";
        BMPString::fromDER("\x1e\x17$str");
    }
}
