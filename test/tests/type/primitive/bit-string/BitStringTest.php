<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Element;
use Sop\ASN1\Type\Primitive\BitString;
use Sop\ASN1\Type\Primitive\NullType;
use Sop\ASN1\Type\UnspecifiedType;

/**
 * @group type
 * @group bit-string
 *
 * @internal
 */
class BitStringTest extends TestCase
{
    public function testCreate()
    {
        $el = new BitString('');
        $this->assertInstanceOf(BitString::class, $el);
        return $el;
    }

    /**
     * @depends testCreate
     */
    public function testTag(Element $el)
    {
        $this->assertEquals(Element::TYPE_BIT_STRING, $el->tag());
    }

    /**
     * @depends testCreate
     */
    public function testEncode(Element $el): string
    {
        $der = $el->toDER();
        $this->assertIsString($der);
        return $der;
    }

    /**
     * @depends testEncode
     */
    public function testDecode(string $data): BitString
    {
        $el = BitString::fromDER($data);
        $this->assertInstanceOf(BitString::class, $el);
        return $el;
    }

    /**
     * @depends testCreate
     * @depends testDecode
     */
    public function testRecoded(Element $ref, Element $el)
    {
        $this->assertEquals($ref, $el);
    }

    /**
     * @dataProvider ffProvider
     */
    public function testRange8(int $start, int $length, string $result)
    {
        $bs = new BitString("\xff");
        $this->assertEquals($result, $bs->range($start, $length));
    }

    public function ffProvider(): array
    {
        return [
            [0, 8, strval(0xff)],
            [1, 2, strval(0x03)],
            [6, 2, strval(0x03)],
            [2, 4, strval(0x0f)],
        ];
    }

    /**
     * @dataProvider ffffProvider
     */
    public function testRange16(int $start, int $length, string $result)
    {
        $bs = new BitString("\xff\xff");
        $this->assertEquals($result, $bs->range($start, $length));
    }

    public function ffffProvider(): array
    {
        return [
            [0, 8, strval(0xff)],
            [6, 4, strval(0x0f)],
            [12, 4, strval(0x0f)],
        ];
    }

    public function testEmptyRange()
    {
        $bs = new BitString("\0");
        $this->assertEquals(0, $bs->range(0, 0));
    }

    public function testRangeOOB()
    {
        $bs = new BitString("\xff");
        $this->expectException(\OutOfBoundsException::class);
        $this->expectExceptionMessage('Not enough bits');
        $bs->range(7, 2);
    }

    /**
     * @depends testCreate
     */
    public function testWrapped(Element $el)
    {
        $wrap = new UnspecifiedType($el);
        $this->assertInstanceOf(BitString::class, $wrap->asBitString());
    }

    public function testWrappedFail()
    {
        $wrap = new UnspecifiedType(new NullType());
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage(
            'BIT STRING expected, got primitive NULL');
        $wrap->asBitString();
    }
}
