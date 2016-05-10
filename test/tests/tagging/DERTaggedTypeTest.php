<?php

use ASN1\Type\Tagged\DERTaggedType;
use ASN1\Type\TaggedType;


/**
 * @group tagging
 */
class DERTaggedTypeTest extends PHPUnit_Framework_TestCase
{
	public function testCreate() {
		$el = TaggedType::fromDER("\xa0\x2\x5\x0");
		$this->assertInstanceOf(DERTaggedType::class, $el);
		return $el;
	}
	
	/**
	 * @depends testCreate
	 * 
	 * @param DERTaggedType $el
	 */
	public function testEncode(DERTaggedType $el) {
		$der = $el->toDER();
		$this->assertEquals("\xa0\x2\x5\x0", $der);
	}
}
