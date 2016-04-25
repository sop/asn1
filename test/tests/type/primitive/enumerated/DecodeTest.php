<?php

use ASN1\Type\Primitive\Enumerated;


/**
 * @group decode
 * @group enumerated
 */
class EnumeratedDecodeTest extends PHPUnit_Framework_TestCase
{
	public function testType() {
		$el = Enumerated::fromDER("\x0a\x1\x0");
		$this->assertInstanceOf(Enumerated::class, $el);
	}
	
	public function testValue() {
		$el = Enumerated::fromDER("\x0a\x1\x1");
		$this->assertEquals(1, $el->number());
	}
}
