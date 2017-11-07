<?php

declare(strict_types = 1);

use ASN1\Component\Identifier;

/**
 * @group decode
 * @group identifier
 */
class IdentifierDecodeTest extends PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $identifier = Identifier::fromDER("\x0");
        $this->assertInstanceOf(Identifier::class, $identifier);
    }
    
    public function testUniversal()
    {
        $identifier = Identifier::fromDER(chr(0b00000000));
        $this->assertTrue($identifier->isUniversal());
    }
    
    public function testApplication()
    {
        $identifier = Identifier::fromDER(chr(0b01000000));
        $this->assertTrue($identifier->isApplication());
    }
    
    public function testContextSpecific()
    {
        $identifier = Identifier::fromDER(chr(0b10000000));
        $this->assertTrue($identifier->isContextSpecific());
    }
    
    public function testPrivate()
    {
        $identifier = Identifier::fromDER(chr(0b11000000));
        $this->assertTrue($identifier->isPrivate());
    }
    
    public function testPC()
    {
        $identifier = Identifier::fromDER(chr(0b00000000));
        $this->assertEquals(Identifier::PRIMITIVE, $identifier->pc());
    }
    
    public function testPrimitive()
    {
        $identifier = Identifier::fromDER(chr(0b00000000));
        $this->assertTrue($identifier->isPrimitive());
    }
    
    public function testConstructed()
    {
        $identifier = Identifier::fromDER(chr(0b00100000));
        $this->assertTrue($identifier->isConstructed());
    }
    
    public function testTag()
    {
        $identifier = Identifier::fromDER(chr(0b00001111));
        $this->assertEquals(0b1111, $identifier->tag());
    }
    
    public function testIntTag()
    {
        $identifier = Identifier::fromDER(chr(0b00001111));
        $this->assertEquals(0b1111, $identifier->intTag());
    }
    
    public function testLongTag()
    {
        $identifier = Identifier::fromDER(chr(0b00011111) . "\x7f");
        $this->assertEquals(0x7f, $identifier->tag());
    }
    
    public function testLongTag2()
    {
        $identifier = Identifier::fromDER(chr(0b00011111) . "\xff\x7f");
        $this->assertEquals((0x7f << 7) + 0x7f, $identifier->tag());
    }
    
    public function testHugeTag()
    {
        $der = "\x1f" . str_repeat("\xff", 100) . "\x7f";
        $identifier = Identifier::fromDER($der);
        $num = gmp_init(str_repeat("1111111", 100) . "1111111", 2);
        $this->assertEquals(gmp_strval($num, 10), $identifier->tag());
    }
    
    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Integer overflow.
     */
    public function testHugeIntTagOverflow()
    {
        $der = "\x1f" . str_repeat("\xff", 100) . "\x7f";
        Identifier::fromDER($der)->intTag();
    }
    
    /**
     * @expectedException ASN1\Exception\DecodeException
     */
    public function testInvalidOffset()
    {
        $offset = 1;
        Identifier::fromDER("\x0", $offset);
    }
    
    /**
     * @expectedException ASN1\Exception\DecodeException
     */
    public function testUnexpectedTagEnd()
    {
        Identifier::fromDER("\x1f\xff");
    }
    
    /**
     * @expectedException \TypeError
     * @expectedExceptionMessageRegExp /must be of the type string/
     */
    public function testInvalidParam()
    {
        Identifier::fromDER(new \stdClass());
    }
}
