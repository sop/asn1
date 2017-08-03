<?php

use ASN1\Element;
use ASN1\Type\UnspecifiedType;
use ASN1\Type\Primitive\IA5String;
use ASN1\Type\Primitive\NullType;

/**
 * @group type
 * @group ia5-string
 */
class IA5StringTest extends PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $el = new IA5String("");
        $this->assertInstanceOf(IA5String::class, $el);
        return $el;
    }
    
    /**
     * @depends testCreate
     *
     * @param Element $el
     */
    public function testTag(Element $el)
    {
        $this->assertEquals(Element::TYPE_IA5_STRING, $el->tag());
    }
    
    /**
     * @depends testCreate
     *
     * @param Element $el
     */
    public function testEncode(Element $el)
    {
        $der = $el->toDER();
        $this->assertInternalType("string", $der);
        return $der;
    }
    
    /**
     * @depends testEncode
     *
     * @param string $data
     */
    public function testDecode($data)
    {
        $el = IA5String::fromDER($data);
        $this->assertInstanceOf(IA5String::class, $el);
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
        $this->assertInstanceOf(IA5String::class, $wrap->asIA5String());
    }
    
    /**
     * @expectedException UnexpectedValueException
     */
    public function testWrappedFail()
    {
        $wrap = new UnspecifiedType(new NullType());
        $wrap->asIA5String();
    }
}
