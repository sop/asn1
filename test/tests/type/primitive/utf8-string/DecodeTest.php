<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Exception\DecodeException;
use Sop\ASN1\Type\Primitive\UTF8String;

/**
 * @group decode
 * @group utf8-string
 *
 * @internal
 */
class UTF8StringDecodeTest extends TestCase
{
    public function testType()
    {
        $el = UTF8String::fromDER("\x0c\x0");
        $this->assertInstanceOf(UTF8String::class, $el);
    }

    public function testValue()
    {
        $str = '⠠⠓⠑⠇⠇⠕ ⠠⠺⠕⠗⠇⠙!';
        $el = UTF8String::fromDER("\x0c\x26{$str}");
        $this->assertEquals($str, $el->string());
    }

    public function testInvalidValue()
    {
        $str = "Hello W\x94rld!";
        $this->expectException(DecodeException::class);
        UTF8String::fromDER("\x0c\x0c{$str}");
    }
}
