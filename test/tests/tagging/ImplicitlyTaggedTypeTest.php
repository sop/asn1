<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Component\Identifier;
use Sop\ASN1\Element;
use Sop\ASN1\Type\Primitive\NullType;
use Sop\ASN1\Type\Tagged\ImplicitlyTaggedType;
use Sop\ASN1\Type\Tagged\ImplicitTagging;
use Sop\ASN1\Type\TaggedType;

/**
 * @group tagging
 * @group implicit-tag
 *
 * @internal
 */
class ImplicitlyTaggedTypeTest extends TestCase
{
    public function testCreate()
    {
        $el = new ImplicitlyTaggedType(1, new NullType());
        $this->assertInstanceOf(ImplicitTagging::class, $el);
        return $el;
    }

    /**
     * @depends testCreate
     */
    public function testGetImplicit(ImplicitTagging $el)
    {
        $this->assertEquals(Element::TYPE_NULL,
            $el->implicit(Element::TYPE_NULL)
                ->tag());
    }

    /**
     * @depends testCreate
     */
    public function testExpectationFail(ImplicitTagging $el)
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage(
            'Type class PRIVATE expected, got UNIVERSAL');
        $el->implicit(Element::TYPE_NULL, Identifier::CLASS_PRIVATE);
    }

    /**
     * @depends testCreate
     */
    public function testExpectImplicit(TaggedType $el)
    {
        $this->assertInstanceOf(ImplicitTagging::class, $el->expectImplicit());
    }

    /**
     * @depends testCreate
     */
    public function testExpectExplicitFail(TaggedType $el)
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage(
            'Element doesn\'t implement explicit tagging');
        $el->expectExplicit();
    }

    /**
     * @depends testCreate
     */
    public function testExpectImplicitWithTag(TaggedType $el)
    {
        $this->assertInstanceOf(ImplicitTagging::class, $el->expectImplicit(1));
    }

    /**
     * @depends testCreate
     */
    public function testExpectImplicitWithInvalidTagFail(TaggedType $el)
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Tag 2 expected, got 1');
        $el->expectImplicit(2);
    }

    /**
     * @depends testCreate
     */
    public function testExpectTypeFails(TaggedType $el)
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage(
            'NULL expected, got CONTEXT SPECIFIC TAG 1');
        $el->expectType(Element::TYPE_NULL);
    }

    /**
     * @depends testCreate
     */
    public function testAsImplicit(TaggedType $el)
    {
        $this->assertInstanceOf(NullType::class,
            $el->asImplicit(Element::TYPE_NULL, 1)
                ->asNull());
    }

    /**
     * @depends testCreate
     */
    public function testAsImplicitFail(TaggedType $el)
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Tag 2 expected, got 1');
        $el->asImplicit(Element::TYPE_NULL, 2);
    }
}
