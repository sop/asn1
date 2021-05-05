<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Element;
use Sop\ASN1\Type\Primitive\NullType;
use Sop\ASN1\Type\Primitive\ObjectIdentifier;
use Sop\ASN1\Type\UnspecifiedType;

/**
 * @group type
 * @group oid
 *
 * @internal
 */
class ObjectIdentifierTest extends TestCase
{
    public function testCreate()
    {
        $el = new ObjectIdentifier('1.3.6.1.3');
        $this->assertInstanceOf(ObjectIdentifier::class, $el);
        return $el;
    }

    /**
     * @depends testCreate
     */
    public function testTag(Element $el)
    {
        $this->assertEquals(Element::TYPE_OBJECT_IDENTIFIER, $el->tag());
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
    public function testDecode(string $data): ObjectIdentifier
    {
        $el = ObjectIdentifier::fromDER($data);
        $this->assertInstanceOf(ObjectIdentifier::class, $el);
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

    /**
     * @depends testCreate
     */
    public function testWrapped(Element $el)
    {
        $wrap = new UnspecifiedType($el);
        $this->assertInstanceOf(ObjectIdentifier::class,
            $wrap->asObjectIdentifier());
    }

    public function testWrappedFail()
    {
        $wrap = new UnspecifiedType(new NullType());
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage(
            'OBJECT IDENTIFIER expected, got primitive NULL');
        $wrap->asObjectIdentifier();
    }

    public function testOnlyRootArc()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('OID must have at least two nodes');
        new ObjectIdentifier('0');
    }

    public function testInvalidRootArc()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Root arc must be in range of 0..2');
        new ObjectIdentifier('3.0');
    }

    public function testInvalidSubarc()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage(
            'Second node must be in 0..39 range for root arcs 0 and 1');
        new ObjectIdentifier('0.40');
    }

    public function testInvalidSubarc1()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage(
            'Second node must be in 0..39 range for root arcs 0 and 1');
        new ObjectIdentifier('1.40');
    }

    /**
     * @requires PHP < 8.0
     */
    public function testInvalidNumberPrePHP8()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('is not a number');
        new ObjectIdentifier('1.1.x');
    }

    /**
     * @requires PHP >= 8.0
     */
    public function testInvalidNumberPHP8()
    {
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('not an integer');
        new ObjectIdentifier('1.1.x');
    }

    /**
     * @dataProvider oidProvider
     *
     * @param string $oid
     */
    public function testOID($oid)
    {
        $x = new ObjectIdentifier($oid);
        $der = $x->toDER();
        $this->assertEquals($oid,
            UnspecifiedType::fromDER($der)->asObjectIdentifier()
                ->oid());
    }

    /**
     * @return string[]
     */
    public function oidProvider()
    {
        return array_map(function ($x) {
            return [$x];
        },
            ['0.0', '0.1', '1.0', '0.0.0', '0.39', '1.39', '2.39', '2.40',
                '2.999999', '2.99999.1', ]);
    }
}
