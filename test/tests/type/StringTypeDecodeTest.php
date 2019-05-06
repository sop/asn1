<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Element;
use Sop\ASN1\Exception\DecodeException;
use Sop\ASN1\Type\StringType;

/**
 * @group decode
 * @group string
 *
 * @internal
 */
class StringTypeDecodeTest extends TestCase
{
    public function testType()
    {
        $el = StringType::fromDER("\x13\x0");
        $this->assertInstanceOf(StringType::class, $el);
    }

    public function testValue()
    {
        $el = StringType::fromDER("\x13\x0bHello World");
        $this->assertEquals('Hello World', $el->string());
    }

    public function testExpectation()
    {
        $el = StringType::fromDER("\x13\x0bHello World");
        $this->assertInstanceOf(StringType::class,
            $el->expectType(Element::TYPE_STRING));
    }

    public function testConstructedFail()
    {
        $this->expectException(DecodeException::class);
        StringType::fromDER("\x34\x0");
    }
}
