<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Element;
use Sop\ASN1\Exception\DecodeException;
use Sop\ASN1\Type\Constructed\Sequence;
use Sop\ASN1\Type\Primitive\Boolean;
use Sop\ASN1\Type\Primitive\NullType;
use Sop\ASN1\Type\Structure;
use Sop\ASN1\Type\Tagged\ImplicitlyTaggedType;

/**
 * @group type
 * @group structure
 *
 * @internal
 */
class StructureTest extends TestCase
{
    /**
     * @dataProvider hasProvider
     *
     * @param int  $idx
     * @param bool $result
     */
    public function testHas(int $idx, bool $result)
    {
        $seq = new Sequence(new NullType(), new Boolean(true), new NullType());
        $this->assertEquals($seq->has($idx), $result);
    }

    public function hasProvider(): array
    {
        return [
            [0, true],
            [1, true],
            [2, true],
            [3, false],
        ];
    }

    /**
     * @dataProvider hasTypeProvider
     *
     * @param int  $idx
     * @param int  $type
     * @param bool $result
     */
    public function testHasType(int $idx, int $type, bool $result)
    {
        $seq = new Sequence(new NullType(), new Boolean(true));
        $this->assertEquals($seq->has($idx, $type), $result);
    }

    public function hasTypeProvider(): array
    {
        return [
            [0, Element::TYPE_NULL, true],
            [0, Element::TYPE_INTEGER, false],
            [1, Element::TYPE_BOOLEAN, true],
            [2, Element::TYPE_NULL, false],
        ];
    }

    public function testExplode()
    {
        $el = new Sequence(new NullType(), new NullType(), new NullType());
        $der = $el->toDER();
        $parts = Structure::explodeDER($der);
        $null = "\x5\x0";
        $this->assertEquals([$null, $null, $null], $parts);
    }

    public function testExplodePrimitiveFail()
    {
        $el = new NullType();
        $der = $el->toDER();
        $this->expectException(DecodeException::class);
        $this->expectExceptionMessage('not constructed');
        Structure::explodeDER($der);
    }

    public function testExplodeIndefiniteFail()
    {
        $el = new Sequence(new NullType());
        $el = $el->withIndefiniteLength();
        $der = $el->toDER();
        $this->expectException(DecodeException::class);
        $this->expectExceptionMessage('not implemented');
        Structure::explodeDER($der);
    }

    public function testReplace()
    {
        $seq = new Sequence(new NullType(), new NullType());
        $seq = $seq->withReplaced(1, new Boolean(true));
        $expected = new Sequence(new NullType(), new Boolean(true));
        $this->assertEquals($expected, $seq);
    }

    public function testReplaceFail()
    {
        $seq = new Sequence(new NullType(), new NullType());
        $this->expectException(OutOfBoundsException::class);
        $seq->withReplaced(2, new Boolean(true));
    }

    public function testInsertFirst()
    {
        $seq = new Sequence(new NullType(), new NullType());
        $seq = $seq->withInserted(0, new Boolean(true));
        $expected = new Sequence(new Boolean(true), new NullType(),
            new NullType());
        $this->assertEquals($expected, $seq);
    }

    public function testInsertBetween()
    {
        $seq = new Sequence(new NullType(), new NullType());
        $seq = $seq->withInserted(1, new Boolean(true));
        $expected = new Sequence(new NullType(), new Boolean(true),
            new NullType());
        $this->assertEquals($expected, $seq);
    }

    public function testInsertLast()
    {
        $seq = new Sequence(new NullType(), new NullType());
        $seq = $seq->withInserted(2, new Boolean(true));
        $expected = new Sequence(new NullType(), new NullType(),
            new Boolean(true));
        $this->assertEquals($expected, $seq);
    }

    public function testInsertOOB()
    {
        $seq = new Sequence(new NullType(), new NullType());
        $this->expectException(OutOfBoundsException::class);
        $seq->withInserted(3, new Boolean(true));
    }

    public function testAppend()
    {
        $seq = new Sequence(new NullType());
        $seq = $seq->withAppended(new Boolean(true));
        $expected = new Sequence(new NullType(), new Boolean(true));
        $this->assertEquals($expected, $seq);
    }

    public function testPrepend()
    {
        $seq = new Sequence(new NullType());
        $seq = $seq->withPrepended(new Boolean(true));
        $expected = new Sequence(new Boolean(true), new NullType());
        $this->assertEquals($expected, $seq);
    }

    public function testRemoveFirst()
    {
        $seq = new Sequence(new NullType(), new Boolean(true), new NullType());
        $seq = $seq->withoutElement(0);
        $expected = new Sequence(new Boolean(true), new NullType());
        $this->assertEquals($expected, $seq);
    }

    public function testRemoveLast()
    {
        $seq = new Sequence(new NullType(), new Boolean(true), new NullType());
        $seq = $seq->withoutElement(2);
        $expected = new Sequence(new NullType(), new Boolean(true));
        $this->assertEquals($expected, $seq);
    }

    public function testRemoveOnly()
    {
        $seq = new Sequence(new NullType());
        $seq = $seq->withoutElement(0);
        $expected = new Sequence();
        $this->assertEquals($expected, $seq);
    }

    public function testRemoveFail()
    {
        $seq = new Sequence(new NullType());
        $this->expectException(OutOfBoundsException::class);
        $seq->withoutElement(1);
    }

    /**
     * Test that cached tagging lookup table is cleared on clone.
     */
    public function testTaggedAfterClone()
    {
        $seq = new Sequence(new ImplicitlyTaggedType(1, new NullType()));
        $seq->hasTagged(1);
        $seq = $seq->withAppended(new ImplicitlyTaggedType(2, new NullType()));
        $this->assertTrue($seq->hasTagged(2));
    }
}
