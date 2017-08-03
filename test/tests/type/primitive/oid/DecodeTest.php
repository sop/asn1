<?php

use ASN1\Type\Primitive\ObjectIdentifier;

/**
 * @group decode
 * @group oid
 */
class ObjectIdentifierDecodeTest extends PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $el = ObjectIdentifier::fromDER("\x6\0");
        $this->assertInstanceOf(ObjectIdentifier::class, $el);
    }
    
    public function testDecode()
    {
        $el = ObjectIdentifier::fromDER("\x06\x06\x2a\x86\x48\x86\xf7\x0d");
        $this->assertEquals("1.2.840.113549", $el->oid());
    }
    
    public function testFirstZero()
    {
        $el = ObjectIdentifier::fromDER("\x6\x1\x0");
        $this->assertEquals("0.0", $el->oid());
    }
    
    public function testFirst39()
    {
        $el = ObjectIdentifier::fromDER("\x6\x1\x27");
        $this->assertEquals("0.39", $el->oid());
    }
    
    public function testFirst40()
    {
        $el = ObjectIdentifier::fromDER("\x6\x1\x28");
        $this->assertEquals("1.0", $el->oid());
    }
    
    public function testFirst41()
    {
        $el = ObjectIdentifier::fromDER("\x6\x1\x29");
        $this->assertEquals("1.1", $el->oid());
    }
    
    public function testFirstHuge()
    {
        // 0x1fffff
        $el = ObjectIdentifier::fromDER("\x6\x3\xff\xff\x7f");
        $this->assertEquals("52428.31", $el->oid());
    }
    
    /**
     * @expectedException ASN1\Exception\DecodeException
     */
    public function testInvalid()
    {
        ObjectIdentifier::fromDER("\x6\x3\xff\xff\xff");
    }
}
