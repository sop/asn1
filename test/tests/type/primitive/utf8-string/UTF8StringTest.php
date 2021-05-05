<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Element;
use Sop\ASN1\Type\Primitive\NullType;
use Sop\ASN1\Type\Primitive\UTF8String;
use Sop\ASN1\Type\UnspecifiedType;

/**
 * @group type
 * @group utf8-string
 *
 * @internal
 */
class UTF8StringTest extends TestCase
{
    public function testCreate()
    {
        $el = new UTF8String('');
        $this->assertInstanceOf(UTF8String::class, $el);
        return $el;
    }

    /**
     * @depends testCreate
     */
    public function testTag(Element $el)
    {
        $this->assertEquals(Element::TYPE_UTF8_STRING, $el->tag());
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
     *
     * @param string $data
     */
    public function testDecode($data): UTF8String
    {
        $el = UTF8String::fromDER($data);
        $this->assertInstanceOf(UTF8String::class, $el);
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

    public function testInvalidString()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Not a valid UTF8String string');
        new UTF8String(hex2bin('ff'));
    }

    /**
     * @depends testCreate
     */
    public function testWrapped(Element $el)
    {
        $wrap = new UnspecifiedType($el);
        $this->assertInstanceOf(UTF8String::class, $wrap->asUTF8String());
    }

    public function testWrappedFail()
    {
        $wrap = new UnspecifiedType(new NullType());
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage(
            'UTF8String expected, got primitive NULL');
        $wrap->asUTF8String();
    }
}
