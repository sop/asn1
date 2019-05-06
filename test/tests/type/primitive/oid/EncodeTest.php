<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Type\Primitive\ObjectIdentifier;

/**
 * @group encode
 * @group oid
 *
 * @internal
 */
class ObjectIdentifierEncodeTest extends TestCase
{
    public function testEmpty()
    {
        $oid = new ObjectIdentifier('');
        $this->assertEquals("\x6\0", $oid->toDER());
    }

    public function testEncodeLong()
    {
        $oid = new ObjectIdentifier('1.2.840.113549');
        $this->assertEquals("\x06\x06\x2a\x86\x48\x86\xf7\x0d", $oid->toDER());
    }
}
