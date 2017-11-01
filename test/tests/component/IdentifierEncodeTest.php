<?php

declare(strict_types=1);

use ASN1\Element;
use ASN1\Component\Identifier;

/**
 * @group encode
 * @group identifier
 */
class IdentifierEncodeTest extends PHPUnit_Framework_TestCase
{
    public function testUniversal()
    {
        $identifier = new Identifier(Identifier::CLASS_UNIVERSAL,
            Identifier::PRIMITIVE, Element::TYPE_BOOLEAN);
        $this->assertEquals(chr(0b00000001), $identifier->toDER());
    }
    
    public function testApplication()
    {
        $identifier = new Identifier(Identifier::CLASS_APPLICATION,
            Identifier::PRIMITIVE, Element::TYPE_BOOLEAN);
        $this->assertEquals(chr(0b01000001), $identifier->toDER());
    }
    
    public function testContextSpecific()
    {
        $identifier = new Identifier(Identifier::CLASS_CONTEXT_SPECIFIC,
            Identifier::PRIMITIVE, Element::TYPE_BOOLEAN);
        $this->assertEquals(chr(0b10000001), $identifier->toDER());
    }
    
    public function testPrivate()
    {
        $identifier = new Identifier(Identifier::CLASS_PRIVATE,
            Identifier::PRIMITIVE, Element::TYPE_BOOLEAN);
        $this->assertEquals(chr(0b11000001), $identifier->toDER());
    }
    
    public function testConstructed()
    {
        $identifier = new Identifier(Identifier::CLASS_UNIVERSAL,
            Identifier::CONSTRUCTED, Element::TYPE_SEQUENCE);
        $this->assertEquals(chr(0b00110000), $identifier->toDER());
    }
    
    public function testLongTag()
    {
        $identifier = new Identifier(Identifier::CLASS_APPLICATION,
            Identifier::CONSTRUCTED, (0x7f << 7) + 0x7f);
        $this->assertEquals(chr(0b01111111) . "\xff\x7f", $identifier->toDER());
    }
    
    public function testHugeTag()
    {
        $num = gmp_init(str_repeat("1111111", 100) . "1111111", 2);
        $identifier = new Identifier(Identifier::CLASS_APPLICATION,
            Identifier::CONSTRUCTED, gmp_strval($num, 10));
        $this->assertEquals(chr(0b01111111) . str_repeat("\xff", 100) . "\x7f",
            $identifier->toDER());
    }
}
