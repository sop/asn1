<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Element;
use Sop\ASN1\Type\Constructed\ConstructedString;
use Sop\ASN1\Type\Primitive\BitString;
use Sop\ASN1\Type\Primitive\NullType;
use Sop\ASN1\Type\Primitive\OctetString;
use Sop\ASN1\Type\UnspecifiedType;

/**
 * @group structure
 * @group string
 *
 * @internal
 */
class ConstructedStringTest extends TestCase
{
    public function testCreate()
    {
        $cs = ConstructedString::createWithTag(
            Element::TYPE_OCTET_STRING,
            new OctetString('Hello'),
            new OctetString('World')
            )->withIndefiniteLength();
        $this->assertInstanceOf(ConstructedString::class, $cs);
        return $cs;
    }

    /**
     * @depends testCreate
     *
     * @param Element $el
     */
    public function testTag(Element $el)
    {
        $this->assertEquals(Element::TYPE_OCTET_STRING, $el->tag());
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
     * @return ConstructedString
     */
    public function testDecode(string $data): ConstructedString
    {
        $el = ConstructedString::fromDER($data);
        $this->assertInstanceOf(ConstructedString::class, $el);
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
     * @param ConstructedString $cs
     */
    public function testStrings(ConstructedString $cs)
    {
        $this->assertEquals(['Hello', 'World'], $cs->strings());
    }

    /**
     * @depends testCreate
     *
     * @param ConstructedString $cs
     */
    public function testStringable(ConstructedString $cs)
    {
        $this->assertEquals('HelloWorld', $cs->string());
        $this->assertEquals('HelloWorld', strval($cs));
    }

    /**
     * @depends testCreate
     *
     * @param ConstructedString $cs
     */
    public function testIsPseudoType(ConstructedString $cs)
    {
        $this->assertTrue($cs->isType(Element::TYPE_CONSTRUCTED_STRING));
    }

    /**
     * @depends testCreate
     *
     * @param ConstructedString $cs
     */
    public function testUnspecified(ConstructedString $cs)
    {
        $ut = new UnspecifiedType($cs);
        $this->assertInstanceOf(ConstructedString::class, $ut->asConstructedString());
    }

    public function testUnspecifiedFail()
    {
        $ut = new UnspecifiedType(new NullType());
        $this->expectException(\UnexpectedValueException::class);
        $ut->asConstructedString();
    }

    public function testCreateFromElements()
    {
        $cs = ConstructedString::create(new OctetString('Hello'),
            new OctetString('World'));
        $this->assertInstanceOf(ConstructedString::class, $cs);
        return $cs;
    }

    /**
     * @depends testCreateFromElements
     */
    public function testFromElementsTag(ConstructedString $cs)
    {
        $this->assertEquals(Element::TYPE_OCTET_STRING, $cs->tag());
    }

    public function testCreateNoElementsFail()
    {
        $this->expectException(\LogicException::class);
        ConstructedString::create();
    }

    public function testCreateMixedElementsFail()
    {
        $this->expectException(\LogicException::class);
        ConstructedString::create(new OctetString('Hello'),
            new BitString('World'));
    }
}
