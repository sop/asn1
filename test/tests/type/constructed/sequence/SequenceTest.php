<?php

use ASN1\Element;
use ASN1\Type\Structure;
use ASN1\Type\UnspecifiedType;
use ASN1\Type\Constructed\Sequence;
use ASN1\Type\Primitive\Boolean;
use ASN1\Type\Primitive\NullType;

/**
 * @group structure
 * @group sequence
 */
class SequenceTest extends PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $seq = new Sequence(new NullType(), new Boolean(true));
        $this->assertInstanceOf(Structure::class, $seq);
        return $seq;
    }
    
    /**
     * @depends testCreate
     *
     * @param Element $el
     */
    public function testTag(Element $el)
    {
        $this->assertEquals(Element::TYPE_SEQUENCE, $el->tag());
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
        $el = Sequence::fromDER($data);
        $this->assertInstanceOf(Sequence::class, $el);
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
     * @param Sequence $seq
     */
    public function testElements(Sequence $seq)
    {
        $elements = $seq->elements();
        $this->assertContainsOnlyInstancesOf(UnspecifiedType::class, $elements);
    }
    
    /**
     * @depends testCreate
     *
     * @param Sequence $seq
     */
    public function testCount(Sequence $seq)
    {
        $this->assertCount(2, $seq);
    }
    
    /**
     * @depends testCreate
     *
     * @param Sequence $seq
     */
    public function testIterator(Sequence $seq)
    {
        $elements = array();
        foreach ($seq as $el) {
            $elements[] = $el;
        }
        $this->assertCount(2, $elements);
        $this->assertContainsOnlyInstancesOf(UnspecifiedType::class, $elements);
    }
    
    /**
     * @depends testCreate
     *
     * @param Sequence $seq
     */
    public function testAt(Sequence $seq)
    {
        $el = $seq->at(0)->asNull();
        $this->assertInstanceOf(NullType::class, $el);
    }
    
    /**
     * @depends testCreate
     *
     * @param Sequence $seq
     */
    public function testAtExpected(Sequence $seq)
    {
        $el = $seq->at(0, Element::TYPE_NULL)->asNull();
        $this->assertInstanceOf(NullType::class, $el);
    }
    
    /**
     * @depends testCreate
     * @expectedException UnexpectedValueException
     *
     * @param Sequence $seq
     */
    public function testAtExpectationFail(Sequence $seq)
    {
        $seq->at(1, Element::TYPE_NULL);
    }
    
    /**
     * @depends testCreate
     * @expectedException OutOfBoundsException
     *
     * @param Sequence $seq
     */
    public function testAtOOB(Sequence $seq)
    {
        $seq->at(2);
    }
    
    /**
     * @depends testCreate
     *
     * @param Element $el
     */
    public function testWrapped(Element $el)
    {
        $wrap = new UnspecifiedType($el);
        $this->assertInstanceOf(Sequence::class, $wrap->asSequence());
    }
    
    /**
     * @expectedException UnexpectedValueException
     */
    public function testWrappedFail()
    {
        $wrap = new UnspecifiedType(new NullType());
        $wrap->asSequence();
    }
}
