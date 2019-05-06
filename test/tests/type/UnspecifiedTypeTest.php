<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\DERData;
use Sop\ASN1\Element;
use Sop\ASN1\Feature\ElementBase;
use Sop\ASN1\Type\Primitive\NullType;
use Sop\ASN1\Type\Tagged\ImplicitlyTaggedType;
use Sop\ASN1\Type\UnspecifiedType;

/**
 * @internal
 */
class UnspecifiedTypeTest extends TestCase
{
    public function testAsElement()
    {
        $wrap = new UnspecifiedType(new NullType());
        $this->assertInstanceOf(ElementBase::class, $wrap->asElement());
        return $wrap;
    }

    public function testAsUnspecified()
    {
        $wrap = new UnspecifiedType(new NullType());
        $this->assertInstanceOf(UnspecifiedType::class, $wrap->asUnspecified());
    }

    public function testFromElementBase()
    {
        $el = new NullType();
        $wrap = UnspecifiedType::fromElementBase($el);
        $this->assertInstanceOf(UnspecifiedType::class, $wrap);
    }

    public function testFromDER()
    {
        $el = UnspecifiedType::fromDER("\x5\0")->asNull();
        $this->assertInstanceOf(NullType::class, $el);
    }

    /**
     * @depends testAsElement
     */
    public function testFromElementBaseAsWrap(UnspecifiedType $type)
    {
        $wrap = UnspecifiedType::fromElementBase($type);
        $this->assertInstanceOf(UnspecifiedType::class, $wrap);
    }

    public function testAsTaggedFail()
    {
        $wrap = new UnspecifiedType(new NullType());
        $this->expectException(UnexpectedValueException::class);
        $wrap->asTagged();
    }

    public function testAsStringFail()
    {
        $wrap = new UnspecifiedType(new NullType());
        $this->expectException(UnexpectedValueException::class);
        $wrap->asString();
    }

    public function testAsTimeFail()
    {
        $wrap = new UnspecifiedType(new NullType());
        $this->expectException(UnexpectedValueException::class);
        $wrap->asTime();
    }

    public function testPrivateTypeFail()
    {
        $el = new DERData("\xdf\x7f\x0");
        $wrap = new UnspecifiedType($el);
        $this->expectException(UnexpectedValueException::class);
        $wrap->asNull();
    }

    public function testToDER()
    {
        $el = new NullType();
        $wrap = new UnspecifiedType($el);
        $this->assertEquals($el->toDER(), $wrap->toDER());
    }

    public function testTypeClass()
    {
        $el = new NullType();
        $wrap = new UnspecifiedType($el);
        $this->assertEquals($el->typeClass(), $wrap->typeClass());
    }

    public function testIsConstructed()
    {
        $el = new NullType();
        $wrap = new UnspecifiedType($el);
        $this->assertEquals($el->isConstructed(), $wrap->isConstructed());
    }

    public function testTag()
    {
        $el = new NullType();
        $wrap = new UnspecifiedType($el);
        $this->assertEquals($el->tag(), $wrap->tag());
    }

    public function testIsType()
    {
        $el = new NullType();
        $wrap = new UnspecifiedType($el);
        $this->assertTrue($wrap->isType(Element::TYPE_NULL));
    }

    public function testExpectType()
    {
        $el = new NullType();
        $wrap = new UnspecifiedType($el);
        $this->assertInstanceOf(ElementBase::class,
            $wrap->expectType(Element::TYPE_NULL));
    }

    public function testIsTagged()
    {
        $el = new NullType();
        $wrap = new UnspecifiedType($el);
        $this->assertEquals($el->isTagged(), $wrap->isTagged());
    }

    public function testExpectTagged()
    {
        $el = new ImplicitlyTaggedType(0, new NullType());
        $wrap = new UnspecifiedType($el);
        $this->assertInstanceOf(ElementBase::class, $wrap->expectTagged(0));
    }
}
