<?php
declare(strict_types = 1);

use ASN1\Element;
use ASN1\Type\Constructed\ConstructedString;

/**
 *
 * @group structure
 * @group string
 *
 * @internal
 */
class ConstructedStringEncodeTest extends PHPUnit_Framework_TestCase
{
    /**
     */
    public function testEncodeDefinite()
    {
        $el = ConstructedString::create(Element::TYPE_OCTET_STRING);
        $this->assertEquals(hex2bin('2400'), $el->toDER());
    }
    
    /**
     */
    public function testEncodeIndefinite()
    {
        $el = ConstructedString::create(Element::TYPE_OCTET_STRING)->withIndefiniteLength();
        $this->assertEquals(hex2bin('24800000'), $el->toDER());
    }
}
