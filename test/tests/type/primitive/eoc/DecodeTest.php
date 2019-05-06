<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Exception\DecodeException;
use Sop\ASN1\Type\Primitive\EOC;

/**
 * @group decode
 * @group eoc
 *
 * @internal
 */
class EOCDecodeTest extends TestCase
{
    public function testType()
    {
        $el = EOC::fromDER("\0\0");
        $this->assertInstanceOf(EOC::class, $el);
    }

    public function testInvalidLength()
    {
        $this->expectException(DecodeException::class);
        EOC::fromDER("\x0\x1\x0");
    }

    public function testNotPrimitive()
    {
        $this->expectException(DecodeException::class);
        EOC::fromDER("\x20\x0");
    }
}
