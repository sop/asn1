<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Exception\DecodeException;
use Sop\ASN1\Type\Primitive\IA5String;

/**
 * @group decode
 * @group ia5-string
 *
 * @internal
 */
class IA5StringDecodeTest extends TestCase
{
    public function testType()
    {
        $el = IA5String::fromDER("\x16\x0");
        $this->assertInstanceOf(IA5String::class, $el);
    }

    public function testValue()
    {
        $str = 'Hello World!';
        $el = IA5String::fromDER("\x16\x0c{$str}");
        $this->assertEquals($str, $el->string());
    }

    public function testInvalidValue()
    {
        $str = "H\xebll\xf8 W\xf6rld!";
        $this->expectException(DecodeException::class);
        IA5String::fromDER("\x16\x0c{$str}");
    }
}
