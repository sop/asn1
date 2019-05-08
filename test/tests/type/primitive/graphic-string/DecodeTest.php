<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Type\Primitive\GraphicString;

/**
 * @group decode
 * @group graphic-string
 *
 * @internal
 */
class GraphicStringDecodeTest extends TestCase
{
    public function testType()
    {
        $el = GraphicString::fromDER("\x19\x0");
        $this->assertInstanceOf(GraphicString::class, $el);
    }

    public function testValue()
    {
        $str = 'Hello World!';
        $el = GraphicString::fromDER("\x19\x0c{$str}");
        $this->assertEquals($str, $el->string());
    }
}
