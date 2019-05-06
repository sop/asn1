<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Element;
use Sop\ASN1\Type\Primitive\Boolean;
use Sop\ASN1\Type\Primitive\NullType;
use Sop\ASN1\Type\Tagged\ImplicitlyTaggedType;

/**
 * @group encode
 * @group tagging
 * @group implicit-tag
 *
 * @internal
 */
class ImplicitlyTaggedEncodeTest extends TestCase
{
    public function testNull()
    {
        $el = new ImplicitlyTaggedType(0, new NullType());
        $this->assertEquals("\x80\x0", $el->toDER());
    }

    public function testLongTag()
    {
        $el = new ImplicitlyTaggedType(255, new NullType());
        $this->assertEquals("\x9f\x81\x7f\x0", $el->toDER());
    }

    public function testRecode()
    {
        $el = new ImplicitlyTaggedType(0, new Boolean(true));
        $this->assertInstanceOf(Boolean::class,
            $el->implicit(Element::TYPE_BOOLEAN)
                ->asBoolean());
    }
}
