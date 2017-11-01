<?php

declare(strict_types=1);

use ASN1\DERData;
use ASN1\Element;
use ASN1\Component\Identifier;
use ASN1\Type\Constructed\Sequence;
use ASN1\Type\Primitive\Boolean;
use ASN1\Type\Primitive\OctetString;

/**
 * @group der-data
 */
class DERDataTest extends PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $el = new DERData("\x5\x0");
        $this->assertEquals(Element::TYPE_NULL, $el->tag());
        return $el;
    }
    
    /**
     * @depends testCreate
     *
     * @param DERData $el
     */
    public function testClass(DERData $el)
    {
        $this->assertEquals(Identifier::CLASS_UNIVERSAL, $el->typeClass());
    }
    
    /**
     * @depends testCreate
     *
     * @param DERData $el
     */
    public function testConstructed(DERData $el)
    {
        $this->assertFalse($el->isConstructed());
    }
    
    /**
     * @depends testCreate
     *
     * @param DERData $el
     */
    public function testEncode(DERData $el)
    {
        $this->assertEquals("\x5\x0", $el->toDER());
    }
    
    public function testEncodeIntoSequence()
    {
        $el = new DERData("\x5\x0");
        $seq = new Sequence($el);
        $this->assertEquals("\x30\x2\x5\x0", $seq->toDER());
    }
    
    public function testEncodeIntoSequenceWithOther()
    {
        $el = new DERData("\x5\x0");
        $seq = new Sequence($el, new Boolean(true));
        $this->assertEquals("\x30\x5\x5\x0\x1\x1\xff", $seq->toDER());
    }
    
    public function testEncodedContentEmpty()
    {
        $el = new DERData("\x5\x0");
        $cls = new ReflectionClass($el);
        $mtd = $cls->getMethod("_encodedContentDER");
        $mtd->setAccessible(true);
        $content = $mtd->invoke($el);
        $this->assertEquals("", $content);
    }
    
    public function testEncodedContentValue()
    {
        $el = new DERData((new OctetString("test"))->toDER());
        $cls = new ReflectionClass($el);
        $mtd = $cls->getMethod("_encodedContentDER");
        $mtd->setAccessible(true);
        $content = $mtd->invoke($el);
        $this->assertEquals("test", $content);
    }
}
