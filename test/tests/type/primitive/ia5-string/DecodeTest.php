<?php

use ASN1\Type\Primitive\IA5String;

/**
 * @group decode
 * @group ia5-string
 */
class IA5StringDecodeTest extends PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $el = IA5String::fromDER("\x16\x0");
        $this->assertInstanceOf(IA5String::class, $el);
    }
    
    public function testValue()
    {
        $str = "Hello World!";
        $el = IA5String::fromDER("\x16\x0c$str");
        $this->assertEquals($str, $el->string());
    }
    
    /**
     * @expectedException ASN1\Exception\DecodeException
     */
    public function testInvalidValue()
    {
        $str = "H\xebll\xf8 W\xf6rld!";
        IA5String::fromDER("\x16\x0c$str");
    }
}
