<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Exception\DecodeException;
use Sop\ASN1\Type\Primitive\Boolean;

/**
 * @group decode
 * @group boolean
 *
 * @internal
 */
class BooleanDecodeTest extends TestCase
{
    public function testType()
    {
        $el = Boolean::fromDER("\x1\x1\x00");
        $this->assertInstanceOf(Boolean::class, $el);
    }

    public function testTrue()
    {
        $el = Boolean::fromDER("\x1\x1\xff");
        $this->assertTrue($el->value());
    }

    public function testFalse()
    {
        $el = Boolean::fromDER("\x1\x1\x00");
        $this->assertFalse($el->value());
    }

    public function testInvalidDER()
    {
        $this->expectException(DecodeException::class);
        Boolean::fromDER("\x1\x1\x55");
    }

    public function testInvalidLength()
    {
        $this->expectException(DecodeException::class);
        Boolean::fromDER("\x1\x2\x00\x00");
    }
}
