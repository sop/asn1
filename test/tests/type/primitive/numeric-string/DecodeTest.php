<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Exception\DecodeException;
use Sop\ASN1\Type\Primitive\NumericString;

/**
 * @group decode
 * @group numeric-string
 *
 * @internal
 */
class NumericStringDecodeTest extends TestCase
{
    public function testType()
    {
        $el = NumericString::fromDER("\x12\x0");
        $this->assertInstanceOf(NumericString::class, $el);
    }

    public function testValue()
    {
        $str = '123 456 789 0';
        $el = NumericString::fromDER("\x12\x0d{$str}");
        $this->assertEquals($str, $el->string());
    }

    public function testInvalidValue()
    {
        $str = '123-456-789-0';
        $this->expectException(DecodeException::class);
        NumericString::fromDER("\x12\x0d{$str}");
    }
}
