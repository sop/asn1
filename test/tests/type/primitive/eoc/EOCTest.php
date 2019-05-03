<?php
declare(strict_types = 1);

use ASN1\Element;
use ASN1\Type\Primitive\EOC;

/**
 *
 * @group type
 * @group eoc
 */
class EOCTest extends PHPUnit_Framework_TestCase
{
    /**
     *
     * @return Element
     */
    public function testCreate(): Element
    {
        $el = new EOC();
        $this->assertInstanceOf(EOC::class, $el);
        return $el;
    }
    
    /**
     *
     * @depends testCreate
     *
     * @param Element $el
     */
    public function testTag(Element $el)
    {
        $this->assertEquals(Element::TYPE_EOC, $el->tag());
    }
    
    /**
     *
     * @depends testCreate
     *
     * @param Element $el
     * @return string
     */
    public function testEncode(Element $el): string
    {
        $der = $el->toDER();
        $this->assertInternalType("string", $der);
        return $der;
    }
    
    /**
     *
     * @depends testEncode
     *
     * @param string $data
     * @return EOC
     */
    public function testDecode($data): EOC
    {
        $el = EOC::fromDER($data);
        $this->assertInstanceOf(EOC::class, $el);
        return $el;
    }
    
    /**
     *
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
}
