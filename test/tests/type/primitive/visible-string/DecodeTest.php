<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Exception\DecodeException;
use Sop\ASN1\Type\Primitive\VisibleString;

/**
 * @group decode
 * @group visible-string
 *
 * @internal
 */
class VisibleStringDecodeTest extends TestCase
{
    public function testType()
    {
        $el = VisibleString::fromDER("\x1a\x0");
        $this->assertInstanceOf(VisibleString::class, $el);
    }

    public function testValue()
    {
        $str = 'Hello World!';
        $el = VisibleString::fromDER("\x1a\x0c{$str}");
        $this->assertEquals($str, $el->string());
    }

    public function testInvalidValue()
    {
        $str = "Hello\nWorld!";
        $this->expectException(DecodeException::class);
        $this->expectExceptionMessage('Not a valid VisibleString string');
        VisibleString::fromDER("\x1a\x0c{$str}");
    }
}
