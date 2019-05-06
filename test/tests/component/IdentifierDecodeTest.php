<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Component\Identifier;
use Sop\ASN1\Exception\DecodeException;

/**
 * @group decode
 * @group identifier
 *
 * @internal
 */
class IdentifierDecodeTest extends TestCase
{
    public function testType()
    {
        $identifier = Identifier::fromDER("\x0");
        $this->assertInstanceOf(Identifier::class, $identifier);
    }

    public function testUniversal()
    {
        $identifier = Identifier::fromDER(chr(0b00000000));
        $this->assertTrue($identifier->isUniversal());
    }

    public function testApplication()
    {
        $identifier = Identifier::fromDER(chr(0b01000000));
        $this->assertTrue($identifier->isApplication());
    }

    public function testContextSpecific()
    {
        $identifier = Identifier::fromDER(chr(0b10000000));
        $this->assertTrue($identifier->isContextSpecific());
    }

    public function testPrivate()
    {
        $identifier = Identifier::fromDER(chr(0b11000000));
        $this->assertTrue($identifier->isPrivate());
    }

    public function testPC()
    {
        $identifier = Identifier::fromDER(chr(0b00000000));
        $this->assertEquals(Identifier::PRIMITIVE, $identifier->pc());
    }

    public function testPrimitive()
    {
        $identifier = Identifier::fromDER(chr(0b00000000));
        $this->assertTrue($identifier->isPrimitive());
    }

    public function testConstructed()
    {
        $identifier = Identifier::fromDER(chr(0b00100000));
        $this->assertTrue($identifier->isConstructed());
    }

    public function testTag()
    {
        $identifier = Identifier::fromDER(chr(0b00001111));
        $this->assertEquals(0b1111, $identifier->tag());
    }

    public function testIntTag()
    {
        $identifier = Identifier::fromDER(chr(0b00001111));
        $this->assertEquals(0b1111, $identifier->intTag());
    }

    public function testLongTag()
    {
        $identifier = Identifier::fromDER(chr(0b00011111) . "\x7f");
        $this->assertEquals(0x7f, $identifier->tag());
    }

    public function testLongTag2()
    {
        $identifier = Identifier::fromDER(chr(0b00011111) . "\xff\x7f");
        $this->assertEquals((0x7f << 7) + 0x7f, $identifier->tag());
    }

    public function testHugeTag()
    {
        $der = "\x1f" . str_repeat("\xff", 100) . "\x7f";
        $identifier = Identifier::fromDER($der);
        $num = gmp_init(str_repeat('1111111', 100) . '1111111', 2);
        $this->assertEquals(gmp_strval($num, 10), $identifier->tag());
    }

    public function testHugeIntTagOverflow()
    {
        $der = "\x1f" . str_repeat("\xff", 100) . "\x7f";
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Integer overflow.');
        Identifier::fromDER($der)->intTag();
    }

    public function testInvalidOffset()
    {
        $offset = 1;
        $this->expectException(DecodeException::class);
        Identifier::fromDER("\x0", $offset);
    }

    public function testUnexpectedTagEnd()
    {
        $this->expectException(DecodeException::class);
        Identifier::fromDER("\x1f\xff");
    }
}
