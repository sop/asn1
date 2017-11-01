<?php

declare(strict_types=1);

use ASN1\Element;
use ASN1\Type\TimeType;
use ASN1\Type\UnspecifiedType;
use ASN1\Type\Primitive\GeneralizedTime;
use ASN1\Type\Primitive\NullType;

/**
 * @group type
 * @group generalized-time
 */
class GeneralizedTimeTest extends PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $el = GeneralizedTime::fromString("Mon Jan 2 15:04:05 MST 2006");
        $this->assertInstanceOf(GeneralizedTime::class, $el);
        return $el;
    }
    
    /**
     * @depends testCreate
     *
     * @param Element $el
     */
    public function testTag(Element $el)
    {
        $this->assertEquals(Element::TYPE_GENERALIZED_TIME, $el->tag());
    }
    
    /**
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
     * @depends testEncode
     *
     * @param string $data
     * @return GeneralizedTime
     */
    public function testDecode(string $data): GeneralizedTime
    {
        $el = GeneralizedTime::fromDER($data);
        $this->assertInstanceOf(GeneralizedTime::class, $el);
        return $el;
    }
    
    /**
     * @depends testCreate
     * @depends testDecode
     *
     * @param TimeType $ref
     * @param TimeType $el
     */
    public function testRecoded(TimeType $ref, TimeType $el)
    {
        $this->assertEquals($ref->dateTime()
            ->getTimestamp(), $el->dateTime()
            ->getTimestamp());
    }
    
    /**
     * @depends testCreate
     *
     * @param Element $el
     */
    public function testWrapped(Element $el)
    {
        $wrap = new UnspecifiedType($el);
        $this->assertInstanceOf(GeneralizedTime::class,
            $wrap->asGeneralizedTime());
    }
    
    /**
     * @expectedException UnexpectedValueException
     */
    public function testWrappedFail()
    {
        $wrap = new UnspecifiedType(new NullType());
        $wrap->asGeneralizedTime();
    }
    
    /**
     * @depends testCreate
     *
     * @param Element $el
     */
    public function testClone(Element $el)
    {
        $clone = clone $el;
        $this->assertInstanceOf(GeneralizedTime::class, $clone);
    }
}
