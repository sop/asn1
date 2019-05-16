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
     *
     * @param ImplicitTagging $el
     */
    public function testGetImplicit(ImplicitTagging $el)
    {
        $this->assertEquals(Element::TYPE_NULL,
            $el->implicit(Element::TYPE_NULL)
                ->tag());
    }

    /**
     * @depends testCreate
     *
     * @param ImplicitTagging $el
     */
    public function testExpectationFail(ImplicitTagging $el)
    {
        $this->expectException(\UnexpectedValueException::class);
        $el->implicit(Element::TYPE_NULL, Identifier::CLASS_PRIVATE);
    }

    /**
     * @depends testCreate
     *
     * @param TaggedType $el
     */
    public function testExpectImplicit(TaggedType $el)
    {
        $this->assertInstanceOf(ImplicitTagging::class, $el->expectImplicit());
    }

    /**
     * @depends testCreate
     *
     * @param TaggedType $el
     */
    public function testExpectExplicitFail(TaggedType $el)
    {
        $this->expectException(\UnexpectedValueException::class);
        $el->expectExplicit();
    }

    /**
     * @depends testCreate
     *
     * @param TaggedType $el
     */
    public function testExpectImplicitWithTag(TaggedType $el)
    {
        $this->assertInstanceOf(ImplicitTagging::class, $el->expectImplicit(1));
    }

    /**
     * @depends testCreate
     *
     * @param TaggedType $el
     */
    public function testExpectImplicitWithInvalidTagFail(TaggedType $el)
    {
        $this->expectException(\UnexpectedValueException::class);
        $el->expectImplicit(2);
    }

    /**
     * @depends testCreate
     *
     * @param TaggedType $el
     */
    public function testExpectTypeFails(TaggedType $el)
    {
        $this->expectException(\UnexpectedValueException::class);
        $el->expectType(Element::TYPE_NULL);
    }

    /**
     * @depends testCreate
     *
     * @param TaggedType $el
     */
    public function testAsImplicit(TaggedType $el)
    {
        $this->assertInstanceOf(NullType::class,
            $el->asImplicit(Element::TYPE_NULL, 1)
                ->asNull());
    }

    /**
     * @depends testCreate
     *
     * @param TaggedType $el
     */
    public function testAsImplicitFail(TaggedType $el)
    {
        $this->expectException(\UnexpectedValueException::class);
        $el->asImplicit(Element::TYPE_NULL, 2);
    }
}
