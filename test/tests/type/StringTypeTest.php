<?php

use ASN1\Type\Primitive\OctetString;


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
}
