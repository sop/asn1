<?php

declare(strict_types=1);

use ASN1\Element;
use ASN1\Type\StringType;
use ASN1\Exception\DecodeException;
use ASN1\Type\PrimitiveString;
use ASN1\Component\Identifier;

/**
 * @group decode
 * @group string
 */
class StringTypeDecodeTest extends PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $el = StringType::fromDER("\x13\x0");
        $this->assertInstanceOf(StringType::class, $el);
    }
    
    public function testValue()
    {
        $el = StringType::fromDER("\x13\x0bHello World");
        $this->assertEquals("Hello World", $el->string());
    }
    
    public function testExpectation()
    {
        $el = StringType::fromDER("\x13\x0bHello World");
        $this->assertInstanceOf(StringType::class,
            $el->expectType(Element::TYPE_STRING));
    }
    
    /**
     * Cover case where primitive string encoding is not primitive.
     */
    public function testConstructedFail()
    {
        $cls = new ReflectionClass(PrimitiveString::class);
        $mtd = $cls->getMethod('_decodeFromDER');
        $mtd->setAccessible(true);
        $identifier = new Identifier(Identifier::CLASS_UNIVERSAL,
            Identifier::CONSTRUCTED, Element::TYPE_OCTET_STRING);
        $offset = 0;
        $this->expectException(DecodeException::class);
        $this->expectExceptionMessage('must be primitive');
        $mtd->invokeArgs(null, [$identifier, "\x34\x0", &$offset]);
    }
}
