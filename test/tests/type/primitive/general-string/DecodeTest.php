<?php

use ASN1\Type\Primitive\GeneralString;

/**
 * @group decode
 * @group general-string
 */
class GeneralStringDecodeTest extends PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $el = GeneralString::fromDER("\x1b\x0");
        $this->assertInstanceOf(GeneralString::class, $el);
    }
    
    public function testValue()
    {
        $str = "Hello World!";
        $el = GeneralString::fromDER("\x1b\x0c$str");
        $this->assertEquals($str, $el->string());
    }
}
