<?php

use ASN1\Type\Primitive\UniversalString;


/**
 * @group universal-string
 */
class UniversalStringTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testInvalidString() {
		new UniversalString("xxx");
	}
}
