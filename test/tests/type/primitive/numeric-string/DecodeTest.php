<?php

use ASN1\Type\Primitive\NumericString;

/**
 * @group decode
 * @group numeric-string
 */
class NumericStringDecodeTest extends PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $el = NumericString::fromDER("\x12\x0");
        $this->assertInstanceOf(NumericString::class, $el);
    }
    
    public function testValue()
    {
        $str = "123 456 789 0";
        $el = NumericString::fromDER("\x12\x0d$str");
        $this->assertEquals($str, $el->string());
    }
    
    /**
     * @expectedException ASN1\Exception\DecodeException
     */
    public function testInvalidValue()
    {
        $str = "123-456-789-0";
        NumericString::fromDER("\x12\x0d$str");
    }
}
