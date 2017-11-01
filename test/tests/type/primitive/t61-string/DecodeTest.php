<?php

declare(strict_types=1);

use ASN1\Type\Primitive\T61String;

/**
 * @group decode
 * @group t61-string
 */
class T61StringDecodeTest extends PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $el = T61String::fromDER("\x14\x0");
        $this->assertInstanceOf(T61String::class, $el);
    }
    
    public function testValue()
    {
        $str = "Hello World!";
        $el = T61String::fromDER("\x14\x0c$str");
        $this->assertEquals($str, $el->string());
    }
}
