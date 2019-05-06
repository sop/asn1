<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Component\Identifier;
use Sop\ASN1\Element;
use Sop\ASN1\Type\Primitive\Integer;
use Sop\ASN1\Type\Tagged\ApplicationType;
use Sop\ASN1\Type\Tagged\ExplicitlyTaggedType;
use Sop\ASN1\Type\Tagged\ImplicitlyTaggedType;
use Sop\ASN1\Type\UnspecifiedType;

/**
 * @group type
 * @group application
 *
 * @internal
 */
class ApplicationTypeTest extends TestCase
{
    public function testImplicitType()
    {
        // Data ::= [APPLICATION 1] IMPLICIT INTEGER
        $el = Element::fromDER("\x41\x01\x2a");
        $this->assertInstanceOf(ApplicationType::class, $el);
        return $el;
    }

    public function testCreateImplicit()
    {
        $el = new ImplicitlyTaggedType(1, new Integer(42),
            Identifier::CLASS_APPLICATION);
        $this->assertEquals("\x41\x01\x2a", $el->toDER());
    }

    /**
     * @depends testImplicitType
     *
     * @param ApplicationType $el
     */
    public function testUnwrapImplicit(ApplicationType $el)
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
        // Data ::= [APPLICATION 1] EXPLICIT INTEGER
        $el = Element::fromDER("\x61\x03\x02\x01\x2a");
        $this->assertInstanceOf(ApplicationType::class, $el);
        return $el;
    }

    public function testCreateExplicit()
    {
        $el = new ExplicitlyTaggedType(1, new Integer(42),
            Identifier::CLASS_APPLICATION);
        $this->assertEquals("\x61\x03\x02\x01\x2a", $el->toDER());
    }

    /**
     * @depends testExplicitType
     *
     * @param ApplicationType $el
     */
    public function testUnwrapExplicit(ApplicationType $el)
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
     *
     * @param ApplicationType $el
     */
    public function testRecodeExplicit(ApplicationType $el)
    {
        $der = $el->toDER();
        $this->assertEquals("\x61\x03\x02\x01\x2a", $der);
    }

    public function testFromUnspecified()
    {
        $el = UnspecifiedType::fromDER("\x41\x01\x2a");
        $this->assertInstanceOf(ApplicationType::class, $el->asApplication());
    }

    public function testFromUnspecifiedFail()
    {
        $el = UnspecifiedType::fromDER("\x5\0");
        $this->expectException(UnexpectedValueException::class);
        $el->asApplication();
    }
}
