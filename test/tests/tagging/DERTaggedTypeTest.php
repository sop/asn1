<?php

declare(strict_types=1);

use ASN1\Element;
use ASN1\Type\TaggedType;
use ASN1\Type\UnspecifiedType;
use ASN1\Type\Tagged\DERTaggedType;

/**
 * @group tagging
 */
class DERTaggedTypeTest extends PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $el = TaggedType::fromDER("\xa0\x2\x5\x0");
        $this->assertInstanceOf(DERTaggedType::class, $el);
        return $el;
    }
    
    /**
     * @depends testCreate
     *
     * @param DERTaggedType $el
     */
    public function testEncode(DERTaggedType $el)
    {
        $der = $el->toDER();
        $this->assertEquals("\xa0\x2\x5\x0", $der);
    }
    
    /**
     * @depends testCreate
     *
     * @param DERTaggedType $el
     */
    public function testExpectExplicit(DERTaggedType $el)
    {
        $this->assertInstanceOf(TaggedType::class, $el->expectExplicit());
    }
    
    /**
     * @depends testCreate
     *
     * @param DERTaggedType $el
     */
    public function testExpectImplicit(DERTaggedType $el)
    {
        $this->assertInstanceOf(TaggedType::class, $el->expectImplicit());
    }
    
    /**
     * @depends testCreate
     *
     * @param Element $el
     */
    public function testWrapped(Element $el)
    {
        $wrap = new UnspecifiedType($el);
        $this->assertInstanceOf(TaggedType::class, $wrap->asTagged());
    }
}
