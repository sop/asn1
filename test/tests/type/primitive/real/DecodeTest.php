<?php

use ASN1\Type\Primitive\Real;


/**
 * @group decode
 * @group real
 */
class RealDecodeTest extends PHPUnit_Framework_TestCase
{
	public function testType() {
		$el = Real::fromDER("\x09\x0");
		$this->assertInstanceOf(Real::class, $el);
	}
}
