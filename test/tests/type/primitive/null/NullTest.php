<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Element;
use Sop\ASN1\Type\Primitive\Boolean;
use Sop\ASN1\Type\Primitive\NullType;
use Sop\ASN1\Type\UnspecifiedType;

/**
 * @group type
 * @group null
 *
 * @internal
 */
class NullTest extends TestCase
{
    public function testCreate()
    {
        $el = new NullType();
        $this->assertInstanceOf(NullType::class, $el);
        return $el;
    }

    /**
     * @depends testCreate
     */
    public function testTag(Element $el)
    {
        $this->assertEquals(Element::TYPE_NULL, $el->tag());
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
    public function testDecode($data): NullType
    {
        $el = NullType::fromDER($data);
        $this->assertInstanceOf(NullType::class, $el);
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
        $this->assertInstanceOf(NullType::class, $wrap->asNull());
    }

    public function testWrappedFail()
    {
        $wrap = new UnspecifiedType(new Boolean(true));
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('NULL expected, got primitive BOOLEAN');
        $wrap->asNull();
    }
}
