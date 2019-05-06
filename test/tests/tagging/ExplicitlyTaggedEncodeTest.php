<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Type\Primitive\Boolean;
use Sop\ASN1\Type\Primitive\NullType;
use Sop\ASN1\Type\Tagged\ExplicitlyTaggedType;

/**
 * @group encode
 * @group tagging
 * @group explicit-tag
 *
 * @internal
 */
class ExplicitlyTaggedEncodeTest extends TestCase
{
    public function testNull()
    {
        $el = new ExplicitlyTaggedType(0, new NullType());
        $this->assertEquals("\xa0\x2\x5\x0", $el->toDER());
    }

    public function testNested()
    {
        $el = new ExplicitlyTaggedType(1,
            new ExplicitlyTaggedType(2, new NullType()));
        $this->assertEquals("\xa1\x4\xa2\x2\x5\x0", $el->toDER());
    }

    public function testLongTag()
    {
        $el = new ExplicitlyTaggedType(255, new NullType());
        $this->assertEquals("\xbf\x81\x7f\x2\x5\x0", $el->toDER());
    }

    public function testRecode()
    {
        $el = new ExplicitlyTaggedType(0, new Boolean(true));
        $this->assertInstanceOf(Boolean::class,
            $el->explicit()
                ->asBoolean());
    }
}
