<?php

use ASN1\DERData;
use ASN1\Element;
use ASN1\Type\Primitive\Boolean;
use ASN1\Type\Constructed\Sequence;


/**
 * @group encode
 */
class DERDataEncodeTest extends PHPUnit_Framework_TestCase
{
	public function testInstance() {
		$el = new DERData("\x5\x0");
		$this->assertEquals(Element::TYPE_NULL, $el->tag());
	}
	
	public function testEncodeIntoSequence() {
		$el = new DERData("\x5\x0");
		$seq = new Sequence($el);
		$this->assertEquals("\x30\x2\x5\x0", $seq->toDER());
	}
	
	public function testEncodeIntoSequenceWithOther() {
		$el = new DERData("\x5\x0");
		$seq = new Sequence($el, new Boolean(true));
		$this->assertEquals("\x30\x5\x5\x0\x1\x1\xff", $seq->toDER());
	}
}
