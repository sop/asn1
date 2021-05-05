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
     */
    public function testTag(Element $el)
    {
        $this->assertEquals(Element::TYPE_SEQUENCE, $el->tag());
    }

    /**
     * @depends testCreate
     */
    public function testEncode(Element $el): string
    {
        $der = $el->toDER();
        $this->assertIsString($der);
        return $der;
    }

    /**
     * @depends testEncode
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
     */
    public function testRecoded(Element $ref, Element $el)
    {
        $this->assertEquals($ref, $el);
    }

    /**
     * @depends testCreate
     */
    public function testElements(Sequence $seq)
    {
        $elements = $seq->elements();
        $this->assertContainsOnlyInstancesOf(UnspecifiedType::class, $elements);
    }

    /**
     * @depends testCreate
     */
    public function testCount(Sequence $seq)
    {
        $this->assertCount(2, $seq);
    }

    /**
     * @depends testCreate
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
     */
    public function testAt(Sequence $seq)
    {
        $el = $seq->at(0)->asNull();
        $this->assertInstanceOf(NullType::class, $el);
    }

    /**
     * @depends testCreate
     */
    public function testAtExpected(Sequence $seq)
    {
        $el = $seq->at(0, Element::TYPE_NULL)->asNull();
        $this->assertInstanceOf(NullType::class, $el);
    }

    /**
     * @depends testCreate
     */
    public function testAtOOB(Sequence $seq)
    {
        $this->expectException(\OutOfBoundsException::class);
        $this->expectExceptionMessage(
            'Structure doesn\'t have an element at index 2');
        $seq->at(2);
    }

    /**
     * @depends testCreate
     */
    public function testWrapped(Element $el)
    {
        $wrap = new UnspecifiedType($el);
        $this->assertInstanceOf(Sequence::class, $wrap->asSequence());
    }

    public function testWrappedFail()
    {
        $wrap = new UnspecifiedType(new NullType());
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('SEQUENCE expected, got primitive NULL');
        $wrap->asSequence();
    }
}
