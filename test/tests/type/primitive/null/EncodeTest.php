<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Type\Primitive\NullType;

/**
 * @group encode
 * @group null
 *
 * @internal
 */
class NullEncodeTest extends TestCase
{
    public function testEncode()
    {
        $el = new NullType();
        $this->assertEquals("\x5\x0", $el->toDER());
    }
}
