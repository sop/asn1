<?php

use ASN1\Type\Primitive\OctetString;
use ASN1\Type\StringType;
use ASN1\Type\UnspecifiedType;


/**
 * @group decode
 * @group string
 */
class StringTypeTest extends PHPUnit_Framework_TestCase
{
	public function testWrapped() {
		$wrap = new UnspecifiedType(new OctetString(""));
		$this->assertInstanceOf(StringType::class, $wrap->asString());
	}
}
