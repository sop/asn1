<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Type\Primitive\OctetString;

/**
 * @group decode
 * @group octet-string
 *
 * @internal
 */
class OctetStringDecodeTest extends TestCase
{
    public function testType()
    {
        $el = OctetString::fromDER("\x4\0");
        $this->assertInstanceOf(OctetString::class, $el);
    }

    public function testHelloWorld()
    {
        $el = OctetString::fromDER("\x4\x0cHello World!");
        $this->assertEquals('Hello World!', $el->string());
    }

    public function testNullString()
    {
        $el = OctetString::fromDER("\x4\x3\x0\x0\x0");
        $this->assertEquals("\0\0\0", $el->string());
    }
}
