<?php

use ASN1\Element;


/**
 * @group element
 */
class ElementTest extends PHPUnit_Framework_TestCase
{
	public function testUnknownTagToName() {
		$this->assertEquals("TAG 100", Element::tagToName(100));
	}
}
