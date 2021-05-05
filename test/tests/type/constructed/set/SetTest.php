<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Element;
use Sop\ASN1\Type\Constructed\Set;
use Sop\ASN1\Type\Primitive\Boolean;
use Sop\ASN1\Type\Primitive\NullType;
use Sop\ASN1\Type\Structure;
use Sop\ASN1\Type\UnspecifiedType;

/**
 * @group structure
 * @group set
 *
 * @internal
 */
class SetTest extends TestCase
{
    public function testCreate()
    {
        $set = new Set(new NullType(), new Boolean(true));
        $this->assertInstanceOf(Structure::class, $set);
        return $set;
    }

    /**
     * @depends testCreate
     */
    public function testTag(Element $el)
    {
        $this->assertEquals(Element::TYPE_SET, $el->tag());
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
    public function testDecode(string $data): Set
    {
        $el = Set::fromDER($data);
        $this->assertInstanceOf(Set::class, $el);
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

    public function testSortSame()
    {
        $set = new Set(new NullType(), new NullType());
        $sorted = $set->sortedSet();
        $this->assertEquals($set, $sorted);
    }

    /**
     * @depends testCreate
     */
    public function testWrapped(Element $el)
    {
        $wrap = new UnspecifiedType($el);
        $this->assertInstanceOf(Set::class, $wrap->asSet());
    }

    public function testWrappedFail()
    {
        $wrap = new UnspecifiedType(new NullType());
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('SET expected, got primitive NULL');
        $wrap->asSet();
    }
}
