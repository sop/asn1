<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Exception\DecodeException;
use Sop\ASN1\Type\Tagged\DERTaggedType;
use Sop\ASN1\Type\TaggedType;

/**
 * @group tagging
 * @group indefinite
 *
 * @internal
 */
class IndefiniteTaggedTest extends TestCase
{
    public function testDecodeIndefinite()
    {
        $el = TaggedType::fromDER(hex2bin('a0800201010000'));
        $this->assertInstanceOf(DERTaggedType::class, $el);
        return $el;
    }

    /**
     * @depends testDecodeIndefinite
     */
    public function testEncodeIndefinite(TaggedType $el)
    {
        $der = $el->toDER();
        $this->assertEquals(hex2bin('a0800201010000'), $der);
    }

    public function testPrimitiveFail()
    {
        $this->expectException(DecodeException::class);
        $this->expectExceptionMessage(
            'Primitive type with indefinite length is not supported');
        TaggedType::fromDER(hex2bin('80800201010000'));
    }
}
