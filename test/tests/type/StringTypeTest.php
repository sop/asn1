<?php

use ASN1\ElementWrapper;
use ASN1\Type\Primitive\OctetString;
use ASN1\Type\StringType;


/**
 * @group decode
 * @group string
 */
class StringTypeTest extends PHPUnit_Framework_TestCase
{
	public function testStr() {
		$el = new OctetString("");
		$this->assertInternalType("string", $el->str());
	}
	
	public function testWrapped() {
		$wrap = new ElementWrapper(new OctetString(""));
		$this->assertInstanceOf(StringType::class, $wrap->asString());
	}
}
