<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Component\Identifier;

/**
 * @group identifier
 *
 * @internal
 */
class IdentifierTest extends TestCase
{
    public function testClassToName()
    {
        $name = Identifier::classToName(Identifier::CLASS_UNIVERSAL);
        $this->assertEquals('UNIVERSAL', $name);
    }

    public function testUnknownClassToName()
    {
        $name = Identifier::classToName(0xff);
        $this->assertEquals('CLASS 255', $name);
    }
}
