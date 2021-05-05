<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Component\Identifier;
use Sop\ASN1\Element;
use Sop\ASN1\Type\Primitive\Integer;
use Sop\ASN1\Type\Tagged\ExplicitlyTaggedType;
use Sop\ASN1\Type\Tagged\ImplicitlyTaggedType;
use Sop\ASN1\Type\Tagged\PrivateType;
use Sop\ASN1\Type\UnspecifiedType;

/**
 * @group type
 * @group private
 *
 * @internal
 */
class PrivateTypeTest extends TestCase
{
    public function testImplicitType()
    {
        // Data ::= [PRIVATE 1] IMPLICIT INTEGER
        $el = Element::fromDER("\xc1\x01\x2a");
        $this->assertInstanceOf(PrivateType::class, $el);
        return $el;
    }

    public function testCreateImplicit()
    {
        $el = new ImplicitlyTaggedType(1, new Integer(42),
            Identifier::CLASS_PRIVATE);
        $this->assertEquals("\xc1\x01\x2a", $el->toDER());
    }

    /**
     * @depends testImplicitType
     */
    public function testUnwrapImplicit(PrivateType $el)
    {
        $inner = $el->implicit(Element::TYPE_INTEGER)->asInteger();
        $this->assertInstanceOf(Integer::class, $inner);
        return $inner;
    }

    /**
     * @depends testUnwrapImplicit
     *
     * @param int $el
     */
    public function testImplicitValue(Integer $el)
    {
        $this->assertEquals(42, $el->intNumber());
    }

    public function testExplicitType()
    {
        // Data ::= [PRIVATE 1] EXPLICIT INTEGER
        $el = Element::fromDER("\xe1\x03\x02\x01\x2a");
        $this->assertInstanceOf(PrivateType::class, $el);
        return $el;
    }

    public function testCreateExplicit()
    {
        $el = new ExplicitlyTaggedType(1, new Integer(42),
            Identifier::CLASS_PRIVATE);
        $this->assertEquals("\xe1\x03\x02\x01\x2a", $el->toDER());
    }

    /**
     * @depends testExplicitType
     */
    public function testUnwrapExplicit(PrivateType $el)
    {
        $inner = $el->explicit()->asInteger();
        $this->assertInstanceOf(Integer::class, $inner);
        return $inner;
    }

    /**
     * @depends testUnwrapExplicit
     *
     * @param int $el
     */
    public function testExplicitValue(Integer $el)
    {
        $this->assertEquals(42, $el->intNumber());
    }

    /**
     * @depends testExplicitType
     */
    public function testRecodeExplicit(PrivateType $el)
    {
        $der = $el->toDER();
        $this->assertEquals("\xe1\x03\x02\x01\x2a", $der);
    }

    public function testFromUnspecified()
    {
        $el = UnspecifiedType::fromDER("\xc1\x01\x2a");
        $this->assertInstanceOf(PrivateType::class, $el->asPrivate());
    }

    public function testFromUnspecifiedFail()
    {
        $el = UnspecifiedType::fromDER("\x5\0");
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage(
            'Private type expected, got primitive NULL');
        $el->asPrivate();
    }
}
