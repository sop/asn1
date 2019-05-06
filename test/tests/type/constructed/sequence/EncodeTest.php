<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Type\Constructed\Sequence;
use Sop\ASN1\Type\Primitive\NullType;

/**
 * @group encode
 * @group structure
 * @group sequence
 *
 * @internal
 */
class SequenceEncodeTest extends TestCase
{
    public function testEncode()
    {
        $el = new Sequence();
        $this->assertEquals("\x30\x0", $el->toDER());
    }

    public function testSingle()
    {
        $el = new Sequence(new NullType());
        $this->assertEquals("\x30\x2\x5\x0", $el->toDER());
    }

    public function testThree()
    {
        $el = new Sequence(new NullType(), new NullType(), new NullType());
        $this->assertEquals("\x30\x6" . str_repeat("\x5\x0", 3), $el->toDER());
    }

    public function testNested()
    {
        $el = new Sequence(new Sequence(new NullType()));
        $this->assertEquals("\x30\x4\x30\x2\x5\x0", $el->toDER());
    }
}
