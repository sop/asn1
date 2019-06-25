<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Exception\DecodeException;
use Sop\ASN1\Type\Constructed\Sequence;
use Sop\ASN1\Type\Constructed\Set;
use Sop\ASN1\Type\Primitive\NullType;
use Sop\ASN1\Type\Structure;
use Sop\ASN1\Type\Tagged\DERTaggedType;

/**
 * @group decode
 * @group structure
 *
 * @internal
 */
class StructureDecodeTest extends TestCase
{
    /**
     * Test too short length.
     */
    public function testTooShort()
    {
        $this->expectException(DecodeException::class);
        $this->expectExceptionMessage(
            'Structure\'s content overflows length');
        Structure::fromDER("\x30\x1\x5\x0");
    }

    /**
     * Test too long length.
     */
    public function testTooLong()
    {
        $this->expectException(DecodeException::class);
        $this->expectExceptionMessage('Length 3 overflows data, 2 bytes left');
        Structure::fromDER("\x30\x3\x5\x0");
    }

    /**
     * Test when structure doesn't have constructed flag.
     */
    public function testNotConstructed()
    {
        $this->expectException(DecodeException::class);
        $this->expectExceptionMessage(
            'Structured element must have constructed bit set');
        Structure::fromDER("\x10\x0");
    }

    public function testImplicitlyTaggedExists()
    {
        // null, tag 0, null
        $set = Set::fromDER("\x31\x6\x5\x0\x80\x0\x5\x0");
        $this->assertTrue($set->hasTagged(0));
    }

    public function testImplicitlyTaggedFetch()
    {
        // null, tag 1, null
        $set = Set::fromDER("\x31\x6\x5\x0\x81\x0\x5\x0");
        $this->assertInstanceOf(DERTaggedType::class, $set->getTagged(1));
    }

    public function testExplicitlyTaggedExists()
    {
        // null, tag 0 (null), null
        $set = Set::fromDER("\x31\x8\x5\x0\xa0\x2\x5\x0\x5\x0");
        $this->assertTrue($set->hasTagged(0));
    }

    public function testExplicitlyTaggedFetch()
    {
        // null, tag 1 (null), null
        $set = Set::fromDER("\x31\x8\x5\x0\xa1\x2\x5\x0\x5\x0");
        $this->assertInstanceOf(DERTaggedType::class, $set->getTagged(1));
        $this->assertInstanceOf(NullType::class,
            $set->getTagged(1)
                ->expectExplicit()
                ->explicit()
                ->asNull());
    }

    public function testInvalidTag()
    {
        // null, tag 0, null
        $set = Set::fromDER("\x31\x6\x5\x0\x80\x0\x5\x0");
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('No tagged element for tag 1');
        $set->getTagged(1);
    }

    public function testIndefinite()
    {
        $seq = Sequence::fromDER(hex2bin('30800201010000'));
        $this->assertInstanceOf(Sequence::class, $seq);
    }

    public function testIndefiniteUnexpectedEnd()
    {
        $this->expectException(DecodeException::class);
        $this->expectExceptionMessage(
            'Unexpected end of data while decoding indefinite length structure');
        Sequence::fromDER(hex2bin('3080020101'));
    }
}
