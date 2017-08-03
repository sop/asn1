<?php

use ASN1\Component\Identifier;

/**
 * @group identifier
 */
class IdentifierTest extends PHPUnit_Framework_TestCase
{
    public function testClassToName()
    {
        $name = Identifier::classToName(Identifier::CLASS_UNIVERSAL);
        $this->assertEquals("UNIVERSAL", $name);
    }
    
    public function testUnknownClassToName()
    {
        $name = Identifier::classToName(0xff);
        $this->assertEquals("CLASS 255", $name);
    }
}
