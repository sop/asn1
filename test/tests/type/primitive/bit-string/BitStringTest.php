<?php

use ASN1\Element;
use ASN1\Type\UnspecifiedType;
use ASN1\Type\Primitive\BitString;
use ASN1\Type\Primitive\NullType;

/**
 * @group type
 * @group bit-string
 */
class BitStringTest extends PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $el = new BitString("");
        $this->assertInstanceOf(BitString::class, $el);
        return $el;
    }
    
    /**
     * @depends testCreate
     *
     * @param Element $el
     */
    public function testTag(Element $el)
    {
        $this->assertEquals(Element::TYPE_BIT_STRING, $el->tag());
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
        $el = BitString::fromDER($data);
        $this->assertInstanceOf(BitString::class, $el);
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
     * @dataProvider ffProvider
     */
    public function testRange8($start, $length, $result)
    {
        $bs = new BitString("\xff");
        $this->assertEquals($result, $bs->range($start, $length));
    }
    
    public function ffProvider()
    {
        return array(
			/* @formatter:off */
			[0, 8, 0xff],
			[1, 2, 0x03],
			[6, 2, 0x03],
			[2, 4, 0x0f]
			/* @formatter:on */
        );
    }
    
    /**
     * @dataProvider ffffProvider
     */
    public function testRange16($start, $length, $result)
    {
        $bs = new BitString("\xff\xff");
        $this->assertEquals($result, $bs->range($start, $length));
    }
    
    public function ffffProvider()
    {
        return array(
			/* @formatter:off */
			[0, 8, 0xff],
			[6, 4, 0x0f],
			[12, 4, 0x0f]
			/* @formatter:on */
        );
    }
    
    public function testEmptyRange()
    {
        $bs = new BitString("\0");
        $this->assertEquals(0, $bs->range(0, 0));
    }
    
    /**
     * @expectedException \OutOfBoundsException
     */
    public function testRangeOOB()
    {
        $bs = new BitString("\xff");
        $bs->range(7, 2);
    }
    
    /**
     * @depends testCreate
     *
     * @param Element $el
     */
    public function testWrapped(Element $el)
    {
        $wrap = new UnspecifiedType($el);
        $this->assertInstanceOf(BitString::class, $wrap->asBitString());
    }
    
    /**
     * @expectedException UnexpectedValueException
     */
    public function testWrappedFail()
    {
        $wrap = new UnspecifiedType(new NullType());
        $wrap->asBitString();
    }
}
