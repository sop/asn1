<?php

use ASN1\Type\Primitive\Enumerated;

/**
 * @group encode
 * @group enumerated
 */
class EnumeratedEncodeTest extends PHPUnit_Framework_TestCase
{
    public function testEncode()
    {
        $el = new Enumerated(1);
        $this->assertEquals("\x0a\x1\x1", $el->toDER());
    }
}
