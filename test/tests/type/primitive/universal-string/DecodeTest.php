<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Exception\DecodeException;
use Sop\ASN1\Type\Primitive\UniversalString;

/**
 * @group decode
 * @group universal-string
 *
 * @internal
 */
class UniversalStringDecodeTest extends TestCase
{
    public function testType()
    {
        $el = UniversalString::fromDER("\x1c\x0");
        $this->assertInstanceOf(UniversalString::class, $el);
    }

    public function testValue()
    {
        $str = "\0\0\0H\0\0\0e\0\0\0l\0\0\0l\0\0\0o";
        $el = UniversalString::fromDER("\x1c\x14{$str}");
        $this->assertEquals($str, $el->string());
    }

    public function testInvalidValue()
    {
        $str = "\0\0\0H\0\0\0e\0\0\0l\0\0\0lo";
        $this->expectException(DecodeException::class);
        UniversalString::fromDER("\x1c\x17{$str}");
    }
}
