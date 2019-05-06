<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Type\Primitive\Enumerated;

/**
 * @group encode
 * @group enumerated
 *
 * @internal
 */
class EnumeratedEncodeTest extends TestCase
{
    public function testEncode()
    {
        $el = new Enumerated(1);
        $this->assertEquals("\x0a\x1\x1", $el->toDER());
    }
}
