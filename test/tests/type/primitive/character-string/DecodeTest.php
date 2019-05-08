<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Type\Primitive\CharacterString;

/**
 * @group decode
 * @group character-string
 *
 * @internal
 */
class CharacterStringDecodeTest extends TestCase
{
    public function testType()
    {
        $el = CharacterString::fromDER("\x1d\x0");
        $this->assertInstanceOf(CharacterString::class, $el);
    }

    public function testValue()
    {
        $str = 'Hello World!';
        $el = CharacterString::fromDER("\x1d\x0c{$str}");
        $this->assertEquals($str, $el->string());
    }
}
