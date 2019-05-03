<?php
declare(strict_types = 1);

use ASN1\Type\Primitive\EOC;

/**
 *
 * @group encode
 * @group eoc
 */
class EOCEncodeTest extends PHPUnit_Framework_TestCase
{
    public function testEncode()
    {
        $el = new EOC();
        $this->assertEquals("\0\0", $el->toDER());
    }
}
