<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Type\Primitive\OctetString;
use Sop\ASN1\Type\StringType;
use Sop\ASN1\Type\UnspecifiedType;

/**
 * @group decode
 * @group string
 *
 * @internal
 */
class StringTypeTest extends TestCase
{
    public function testWrapped()
    {
        $wrap = new UnspecifiedType(new OctetString(''));
        $this->assertInstanceOf(StringType::class, $wrap->asString());
    }
}
