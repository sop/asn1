<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Element;
use Sop\ASN1\Type\Primitive\NullType;
use Sop\ASN1\Type\Primitive\RelativeOID;
use Sop\ASN1\Type\UnspecifiedType;

/**
 * @group type
 * @group oid
 *
 * @internal
 */
class RelativeOIDTest extends TestCase
{
    public function testCreate()
    {
        $el = new RelativeOID('1.3.6.1.3');
        $this->assertInstanceOf(RelativeOID::class, $el);
        return $el;
    }

    /**
     * @depends testCreate
     */
    public function testTag(Element $el)
    {
        $this->assertEquals(Element::TYPE_RELATIVE_OID, $el->tag());
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
    public function testDecode(string $data): RelativeOID
    {
        $el = RelativeOID::fromDER($data);
        $this->assertInstanceOf(RelativeOID::class, $el);
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
        $this->assertInstanceOf(RelativeOID::class, $wrap->asRelativeOID());
    }

    public function testWrappedFail()
    {
        $wrap = new UnspecifiedType(new NullType());
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage(
            'RELATIVE-OID expected, got primitive NULL');
        $wrap->asRelativeOID();
    }
}
