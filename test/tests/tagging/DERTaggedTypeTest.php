<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Element;
use Sop\ASN1\Type\Tagged\DERTaggedType;
use Sop\ASN1\Type\TaggedType;
use Sop\ASN1\Type\UnspecifiedType;

/**
 * @group tagging
 *
 * @internal
 */
class DERTaggedTypeTest extends TestCase
{
    public function testCreate()
    {
        $el = TaggedType::fromDER("\xa0\x2\x5\x0");
        $this->assertInstanceOf(DERTaggedType::class, $el);
        return $el;
    }

    /**
     * @depends testCreate
     */
    public function testEncode(DERTaggedType $el)
    {
        $der = $el->toDER();
        $this->assertEquals("\xa0\x2\x5\x0", $der);
    }

    /**
     * @depends testCreate
     */
    public function testExpectExplicit(DERTaggedType $el)
    {
        $this->assertInstanceOf(TaggedType::class, $el->expectExplicit());
    }

    /**
     * @depends testCreate
     */
    public function testExpectImplicit(DERTaggedType $el)
    {
        $this->assertInstanceOf(TaggedType::class, $el->expectImplicit());
    }

    /**
     * @depends testCreate
     */
    public function testWrapped(Element $el)
    {
        $wrap = new UnspecifiedType($el);
        $this->assertInstanceOf(TaggedType::class, $wrap->asTagged());
    }
}
