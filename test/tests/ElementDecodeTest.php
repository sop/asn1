<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Component\Identifier;
use Sop\ASN1\Element;
use Sop\ASN1\Type\Primitive\Boolean;
use Sop\ASN1\Type\Primitive\NullType;

/**
 * @group decode
 * @group element
 *
 * @internal
 */
class ElementDecodeTest extends TestCase
{
    public function testAbstract()
    {
        $el = Element::fromDER("\x5\x0");
        $this->assertInstanceOf(NullType::class, $el);
    }

    public function testConcrete()
    {
        $el = NullType::fromDER("\x5\x0");
        $this->assertInstanceOf(NullType::class, $el);
    }

    public function testConcreteWrongClass()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage(
            Boolean::class . ' expected, got ' . NullType::class);
        Boolean::fromDER("\x5\x0");
    }

    public function testUnimplementedFail()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('not implemented');
        Element::fromDER("\x1f\x7f\x0");
    }

    public function testExpectTaggedFail()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage(
            'Context specific element expected, got UNIVERSAL');
        Element::fromDER("\x5\x0")->expectTagged();
    }

    public function testFromDERBadCall()
    {
        $cls = new ReflectionClass(Element::class);
        $mtd = $cls->getMethod('_decodeFromDER');
        $mtd->setAccessible(true);
        $identifier = new Identifier(Identifier::CLASS_UNIVERSAL,
            Identifier::PRIMITIVE, Element::TYPE_NULL);
        $offset = 0;
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('must be implemented in derived class');
        $mtd->invokeArgs(null, [$identifier, '', &$offset]);
    }

    public function testFromUnimplementedClass()
    {
        $cls = new ReflectionClass(Element::class);
        $mtd = $cls->getMethod('_determineImplClass');
        $mtd->setAccessible(true);
        $identifier = new ElementDecodeTest_IdentifierMockup(0, 0, 0);
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('not implemented');
        $mtd->invokeArgs(null, [$identifier]);
    }
}

class ElementDecodeTest_IdentifierMockup extends Identifier
{
    public function typeClass(): int
    {
        return 0xff;
    }
}
