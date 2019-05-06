<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Element;
use Sop\ASN1\Type\Constructed\Sequence;

/**
 * @group decode
 * @group structure
 * @group sequence
 *
 * @internal
 */
class SequenceDecodeTest extends TestCase
{
    public function testType()
    {
        $el = Sequence::fromDER("\x30\x0");
        $this->assertInstanceOf(Sequence::class, $el);
    }

    public function testSingle()
    {
        $el = Sequence::fromDER("\x30\x2\x5\x0");
        $this->assertCount(1, $el);
    }

    public function testThree()
    {
        $el = Sequence::fromDER("\x30\x6" . str_repeat("\x5\x0", 3));
        $this->assertCount(3, $el);
    }

    public function testNested()
    {
        $el = Sequence::fromDER("\x30\x2\x30\x0");
        $this->assertCount(1, $el);
        $this->assertEquals(Element::TYPE_SEQUENCE,
            $el->at(0)
                ->tag());
    }
}
