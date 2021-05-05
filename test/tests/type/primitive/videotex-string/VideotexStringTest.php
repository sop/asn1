<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Element;
use Sop\ASN1\Type\Primitive\NullType;
use Sop\ASN1\Type\Primitive\VideotexString;
use Sop\ASN1\Type\UnspecifiedType;

/**
 * @group type
 * @group videotex-string
 *
 * @internal
 */
class VideotexStringTest extends TestCase
{
    public function testCreate()
    {
        $el = new VideotexString('');
        $this->assertInstanceOf(VideotexString::class, $el);
        return $el;
    }

    /**
     * @depends testCreate
     */
    public function testTag(Element $el)
    {
        $this->assertEquals(Element::TYPE_VIDEOTEX_STRING, $el->tag());
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
    public function testDecode(string $data): VideotexString
    {
        $el = VideotexString::fromDER($data);
        $this->assertInstanceOf(VideotexString::class, $el);
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
     * @depends testCreate
     */
    public function testWrapped(Element $el)
    {
        $wrap = new UnspecifiedType($el);
        $this->assertInstanceOf(VideotexString::class, $wrap->asVideotexString());
    }

    public function testWrappedFail()
    {
        $wrap = new UnspecifiedType(new NullType());
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage(
            'VideotexString expected, got primitive NULL');
        $wrap->asVideotexString();
    }
}
