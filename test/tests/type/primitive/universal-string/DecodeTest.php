<?php

use ASN1\Type\Primitive\UniversalString;

/**
 * @group decode
 * @group universal-string
 */
class UniversalStringDecodeTest extends PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $el = UniversalString::fromDER("\x1c\x0");
        $this->assertInstanceOf(UniversalString::class, $el);
    }
    
    public function testValue()
    {
        $str = "\0\0\0H\0\0\0e\0\0\0l\0\0\0l\0\0\0o";
        $el = UniversalString::fromDER("\x1c\x14$str");
        $this->assertEquals($str, $el->string());
    }
    
    /**
     * @expectedException ASN1\Exception\DecodeException
     */
    public function testInvalidValue()
    {
        $str = "\0\0\0H\0\0\0e\0\0\0l\0\0\0lo";
        UniversalString::fromDER("\x1c\x17$str");
    }
}
