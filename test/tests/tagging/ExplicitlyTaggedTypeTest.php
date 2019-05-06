<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Element;
use Sop\ASN1\Type\Primitive\NullType;
use Sop\ASN1\Type\Tagged\ExplicitlyTaggedType;
use Sop\ASN1\Type\Tagged\ExplicitTagging;
use Sop\ASN1\Type\TaggedType;

/**
 * @group tagging
 * @group explicit-tag
 *
 * @internal
 */
class ExplicitlyTaggedTypeTest extends TestCase
{
    public function testCreate()
    {
        $el = new ExplicitlyTaggedType(1, new NullType());
        $this->assertInstanceOf(ExplicitTagging::class, $el);
        return $el;
    }

    /**
     * @depends testCreate
     *
     * @param ExplicitTagging $el
     */
    public function testGetExplicit(ExplicitTagging $el)
    {
        $this->assertEquals(Element::TYPE_NULL,
            $el->explicit()
                ->tag());
    }

    /**
     * @depends testCreate
     *
     * @param ExplicitTagging $el
     */
    public function testExpectation(ExplicitTagging $el)
    {
        $this->assertInstanceOf(NullType::class,
            $el->explicit(Element::TYPE_NULL)
                ->asNull());
    }

    /**
     * @depends testCreate
     *
     * @param ExplicitTagging $el
     */
    public function testExpectationFail(ExplicitTagging $el)
    {
        $this->expectException(UnexpectedValueException::class);
        $el->explicit(Element::TYPE_BOOLEAN);
    }

    /**
     * @depends testCreate
     *
     * @param ExplicitlyTaggedType $el
     */
    public function testExpectTagged(ExplicitlyTaggedType $el)
    {
        $this->assertInstanceOf(TaggedType::class, $el->expectTagged());
    }

    /**
     * @depends testCreate
     *
     * @param ExplicitlyTaggedType $el
     */
    public function testExpectTag(ExplicitlyTaggedType $el)
    {
        $this->assertInstanceOf(TaggedType::class, $el->expectTagged(1));
    }

    /**
     * @depends testCreate
     *
     * @param ExplicitlyTaggedType $el
     */
    public function testExpectTagFail(ExplicitlyTaggedType $el)
    {
        $this->expectException(UnexpectedValueException::class);
        $el->expectTagged(2);
    }

    /**
     * @depends testCreate
     *
     * @param TaggedType $el
     */
    public function testExpectExplicit(TaggedType $el)
    {
        $this->assertInstanceOf(ExplicitTagging::class, $el->expectExplicit());
    }

    /**
     * @depends testCreate
     *
     * @param TaggedType $el
     */
    public function testExpectImplicitFail(TaggedType $el)
    {
        $this->expectException(UnexpectedValueException::class);
        $el->expectImplicit();
    }

    /**
     * @depends testCreate
     *
     * @param TaggedType $el
     */
    public function testExpectExplicitWithTag(TaggedType $el)
    {
        $this->assertInstanceOf(ExplicitTagging::class, $el->expectExplicit(1));
    }

    /**
     * @depends testCreate
     *
     * @param TaggedType $el
     */
    public function testExpectExplicitWithInvalidTagFail(TaggedType $el)
    {
        $this->expectException(UnexpectedValueException::class);
        $el->expectExplicit(2);
    }

    /**
     * @depends testCreate
     *
     * @param TaggedType $el
     */
    public function testExpectTypeFails(TaggedType $el)
    {
        $this->expectException(UnexpectedValueException::class);
        $el->expectType(Element::TYPE_NULL);
    }

    /**
     * @depends testCreate
     *
     * @param TaggedType $el
     */
    public function testAsExplicit(TaggedType $el)
    {
        $this->assertInstanceOf(NullType::class,
            $el->asExplicit(1)
                ->asNull());
    }

    /**
     * @depends testCreate
     *
     * @param TaggedType $el
     */
    public function testAsExplicitFail(TaggedType $el)
    {
        $this->expectException(UnexpectedValueException::class);
        $el->asExplicit(2);
    }
}
