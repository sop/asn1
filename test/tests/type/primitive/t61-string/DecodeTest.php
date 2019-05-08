<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Type\Primitive\T61String;

/**
 * @group decode
 * @group t61-string
 *
 * @internal
 */
class T61StringDecodeTest extends TestCase
{
    public function testType()
    {
        $el = T61String::fromDER("\x14\x0");
        $this->assertInstanceOf(T61String::class, $el);
    }

    public function testValue()
    {
        $str = 'Hello World!';
        $el = T61String::fromDER("\x14\x0c{$str}");
        $this->assertEquals($str, $el->string());
    }
}
