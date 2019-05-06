<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Element;
use Sop\ASN1\Type\Primitive\GraphicString;
use Sop\ASN1\Type\Primitive\NullType;
use Sop\ASN1\Type\UnspecifiedType;

/**
 * @group type
 * @group graphic-string
 *
 * @internal
 */
class GraphicStringTest extends TestCase
{
    public function testCreate()
    {
        $el = new GraphicString('');
        $this->assertInstanceOf(GraphicString::class, $el);
        return $el;
    }

    /**
     * @depends testCreate
     *
     * @param Element $el
     */
    public function testTag(Element $el)
    {
        $this->assertEquals(Element::TYPE_GRAPHIC_STRING, $el->tag());
    }

    /**
     * @depends testCreate
     *
     * @param Element $el
     *
     * @return string
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
     *
     * @return GraphicString
     */
    public function testDecode(string $data): GraphicString
    {
        $el = GraphicString::fromDER($data);
        $this->assertInstanceOf(GraphicString::class, $el);
        return $el;
    }

    /**
     * @depends testCreate
     * @depends testDecode
     *
     * @param Element $ref
     * @param Element $el
     */
    public function testRecoded(Element $ref, Element $el)
    {
        $this->assertEquals($ref, $el);
    }

    /**
     * @depends testCreate
     *
     * @param Element $el
     */
    public function testWrapped(Element $el)
    {
        $wrap = new UnspecifiedType($el);
        $this->assertInstanceOf(GraphicString::class, $wrap->asGraphicString());
    }

    public function testWrappedFail()
    {
        $wrap = new UnspecifiedType(new NullType());
        $this->expectException(UnexpectedValueException::class);
        $wrap->asGraphicString();
    }
}
