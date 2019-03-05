<?php
declare(strict_types = 1);

use ASN1\Element;
use ASN1\Type\UnspecifiedType;
use ASN1\Type\Primitive\NullType;
use ASN1\Type\Primitive\ObjectIdentifier;

/**
 *
 * @group type
 * @group oid
 */
class ObjectIdentifierTest extends PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $el = new ObjectIdentifier("1.3.6.1.3");
        $this->assertInstanceOf(ObjectIdentifier::class, $el);
        return $el;
    }
    
    /**
     *
     * @depends testCreate
     *
     * @param Element $el
     */
    public function testTag(Element $el)
    {
        $this->assertEquals(Element::TYPE_OBJECT_IDENTIFIER, $el->tag());
    }
    
    /**
     *
     * @depends testCreate
     *
     * @param Element $el
     * @return string
     */
    public function testEncode(Element $el): string
    {
        $der = $el->toDER();
        $this->assertInternalType("string", $der);
        return $der;
    }
    
    /**
     *
     * @depends testEncode
     *
     * @param string $data
     * @return ObjectIdentifier
     */
    public function testDecode(string $data): ObjectIdentifier
    {
        $el = ObjectIdentifier::fromDER($data);
        $this->assertInstanceOf(ObjectIdentifier::class, $el);
        return $el;
    }
    
    /**
     *
     * @depends testCreate
     * @depends testDecode
     *
     * @param Element $ref
     * @param Element $el
     */
    public function testRecoded(Element $ref, Element $el)
    {
        $this->assertEquals($ref, $el);
    }
    
    /**
     *
     * @depends testCreate
     *
     * @param Element $el
     */
    public function testWrapped(Element $el)
    {
        $wrap = new UnspecifiedType($el);
        $this->assertInstanceOf(ObjectIdentifier::class,
            $wrap->asObjectIdentifier());
    }
    
    /**
     *
     * @expectedException UnexpectedValueException
     */
    public function testWrappedFail()
    {
        $wrap = new UnspecifiedType(new NullType());
        $wrap->asObjectIdentifier();
    }
    
    /**
     *
     * @expectedException UnexpectedValueException
     */
    public function testOnlyRootArc()
    {
        new ObjectIdentifier('0');
    }
    
    /**
     *
     * @expectedException UnexpectedValueException
     */
    public function testInvalidRootArc()
    {
        new ObjectIdentifier('3.0');
    }
    
    /**
     *
     * @expectedException UnexpectedValueException
     */
    public function testInvalidSubarc()
    {
        new ObjectIdentifier('0.40');
    }
    
    /**
     *
     * @expectedException UnexpectedValueException
     */
    public function testInvalidSubarc1()
    {
        new ObjectIdentifier('1.40');
    }
    
    /**
     *
     * @expectedException UnexpectedValueException
     */
    public function testInvalidNumber()
    {
        new ObjectIdentifier('1.1.x');
    }
    
    /**
     *
     * @dataProvider oidProvider
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
     *
     * @return string[]
     */
    public function oidProvider()
    {
        return array_map(function ($x) {
            return [$x];
        },
            ['0.0', '0.1', '1.0', '0.0.0', '0.39', '1.39', '2.39', '2.40',
                '2.999999', '2.99999.1']);
    }
}
