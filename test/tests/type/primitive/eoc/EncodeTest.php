<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Type\Primitive\EOC;

/**
 * @group encode
 * @group eoc
 *
 * @internal
 */
class EOCEncodeTest extends TestCase
{
    public function testEncode()
    {
        $el = new EOC();
        $this->assertEquals("\0\0", $el->toDER());
    }
}
