<?php

declare(strict_types=1);

use ASN1\Type\Primitive\PrintableString;

/**
 * @group decode
 * @group printable-string
 */
class PrintableStringDecodeTest extends PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $el = PrintableString::fromDER("\x13\x0");
        $this->assertInstanceOf(PrintableString::class, $el);
    }
    
    public function testValue()
    {
        $str = "Hello World.";
        $el = PrintableString::fromDER("\x13\x0c$str");
        $this->assertEquals($str, $el->string());
    }
    
    /**
     * @expectedException ASN1\Exception\DecodeException
     */
    public function testInvalidValue()
    {
        $str = "Hello World!";
        PrintableString::fromDER("\x13\x0c$str");
    }
}
