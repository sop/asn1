<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Element;
use Sop\ASN1\Type\Constructed\Sequence;
use Sop\ASN1\Type\Primitive\Boolean;
use Sop\ASN1\Type\Primitive\NullType;
use Sop\ASN1\Type\Structure;
use Sop\ASN1\Type\UnspecifiedType;

/**
 * @group structure
 * @group sequence
 *
 * @internal
 */
class SequenceTest extends TestCase
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
    public function testDecode(string $data): Sequence
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
        $elements = [];
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
     *
     * @param Sequence $seq
     */
    public function testAtOOB(Sequence $seq)
    {
        $this->expectException(OutOfBoundsException::class);
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

    public function testWrappedFail()
    {
        $wrap = new UnspecifiedType(new NullType());
        $this->expectException(UnexpectedValueException::class);
        $wrap->asSequence();
    }
}
