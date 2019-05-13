<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Type\Constructed\ConstructedString;
use Sop\ASN1\Type\Primitive\NullType;

/**
 * @group structure
 * @group string
 *
 * @internal
 */
class ConstructedStringDecodeTest extends TestCase
{
    public function testDecodeDefinite()
    {
        $el = ConstructedString::fromDER(hex2bin('2400'));
        $this->assertInstanceOf(ConstructedString::class, $el);
        $this->assertFalse($el->hasIndefiniteLength());
    }

    public function testDecodeIndefinite()
    {
        $el = ConstructedString::fromDER(hex2bin('24800000'));
        $this->assertInstanceOf(ConstructedString::class, $el);
        $this->assertTrue($el->hasIndefiniteLength());
    }

    public function testInvalidCallingClass()
    {
        $this->expectException(\UnexpectedValueException::class);
        NullType::fromDER(hex2bin('2400'));
    }
}
