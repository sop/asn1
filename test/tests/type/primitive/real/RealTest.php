<?php

use ASN1\Element;
use ASN1\Type\UnspecifiedType;
use ASN1\Type\Primitive\NullType;
use ASN1\Type\Primitive\Real;

/**
 * @group type
 * @group real
 */
class RealTest extends PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $el = new Real("314.E-2");
        $this->assertInstanceOf(Real::class, $el);
        return $el;
    }
    
    /**
     * @depends testCreate
     *
     * @param Element $el
     */
    public function testTag(Element $el)
    {
        $this->assertEquals(Element::TYPE_REAL, $el->tag());
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
        $el = Real::fromDER($data);
        $this->assertInstanceOf(Real::class, $el);
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
     * @dataProvider provideFromFloat
     *
     * @param float $number
     */
    public function testFromFloat($number)
    {
        $real = Real::fromFloat($number);
        $recoded = Real::fromDER($real->toDER());
        $this->assertEquals($number, $recoded->float());
    }
    
    public function provideFromFloat()
    {
        return array(
			/* @formatter:off */
			[0],
			[1],
			[-1],
			[1000000],
			[-1000000],
			[0.00001],
			[-0.00001],
			[0.1e-20],
			[-0.1e-20],
			[M_PI],
			[-M_PI],
			[M_E],
			[-M_E]
			/* @formatter:on */
        );
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidFormatFail()
    {
        new Real("fail");
    }
    
    /**
     * @expectedException UnexpectedValueException
     */
    public function testNR3ToDecimalInvalidFormatFail()
    {
        $real = new Real(Real::NR3_ZERO);
        $cls = new ReflectionClass($real);
        $prop = $cls->getProperty("_number");
        $prop->setAccessible(true);
        $prop->setValue($real, "fail");
        $real->float();
    }
    
    /**
     * @depends testCreate
     *
     * @param Element $el
     */
    public function testWrapped(Element $el)
    {
        $wrap = new UnspecifiedType($el);
        $this->assertInstanceOf(Real::class, $wrap->asReal());
    }
    
    /**
     * @expectedException UnexpectedValueException
     */
    public function testWrappedFail()
    {
        $wrap = new UnspecifiedType(new NullType());
        $wrap->asReal();
    }
}
