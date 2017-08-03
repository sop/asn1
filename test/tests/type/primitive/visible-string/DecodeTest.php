<?php

use ASN1\Type\Primitive\VisibleString;

/**
 * @group decode
 * @group visible-string
 */
class VisibleStringDecodeTest extends PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $el = VisibleString::fromDER("\x1a\x0");
        $this->assertInstanceOf(VisibleString::class, $el);
    }
    
    public function testValue()
    {
        $str = "Hello World!";
        $el = VisibleString::fromDER("\x1a\x0c$str");
        $this->assertEquals($str, $el->string());
    }
    
    /**
     * @expectedException ASN1\Exception\DecodeException
     */
    public function testInvalidValue()
    {
        $str = "Hello\nWorld!";
        VisibleString::fromDER("\x1a\x0c$str");
    }
}
