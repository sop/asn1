<?php

declare(strict_types=1);

use ASN1\Type\StringType;
use ASN1\Type\UnspecifiedType;
use ASN1\Type\Primitive\OctetString;

/**
 * @group decode
 * @group string
 */
class StringTypeTest extends PHPUnit_Framework_TestCase
{
    public function testWrapped()
    {
        $wrap = new UnspecifiedType(new OctetString(""));
        $this->assertInstanceOf(StringType::class, $wrap->asString());
    }
}
