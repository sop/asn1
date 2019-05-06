<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Type\Primitive\VideotexString;

/**
 * @group decode
 * @group videotex-string
 *
 * @internal
 */
class VideotexStringDecodeTest extends TestCase
{
    public function testType()
    {
        $el = VideotexString::fromDER("\x15\x0");
        $this->assertInstanceOf(VideotexString::class, $el);
    }

    public function testValue()
    {
        $str = 'Hello World!';
        $el = VideotexString::fromDER("\x15\x0c${str}");
        $this->assertEquals($str, $el->string());
    }
}
