<?php

declare(strict_types = 1);

use ASN1\Element;
use ASN1\Component\Identifier;
use ASN1\Type\UnspecifiedType;
use ASN1\Type\Primitive\Integer;
use ASN1\Type\Tagged\ApplicationType;
use ASN1\Type\Tagged\ExplicitlyTaggedType;
use ASN1\Type\Tagged\ImplicitlyTaggedType;
use PHPUnit\Framework\TestCase;

/**
 * @group type
 * @group application
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
    
    public function getCreateImplicit()
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
     * @param Integer $el
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
     * @param Integer $el
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
    
    /**
     * @expectedException UnexpectedValueException
     */
    public function testFromUnspecifiedFail()
    {
        $el = UnspecifiedType::fromDER("\x5\0");
        $el->asApplication();
    }
}