<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Element;
use Sop\ASN1\Type\Constructed\ConstructedString;
use Sop\ASN1\Type\Constructed\Sequence;
use Sop\ASN1\Type\Primitive\OctetString;

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
        $cs = ConstructedString::create(
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
     * @return Sequence
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
    public function testConcatenated(ConstructedString $cs)
    {
        $this->assertEquals('HelloWorld', $cs->concatenated());
    }
}
