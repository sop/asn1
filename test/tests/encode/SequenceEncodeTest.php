<?php

use ASN1\Type\Constructed\Sequence;
use ASN1\Type\Primitive\NullType;


/**
 * @group encode
 */
class SequenceEncodeTest extends PHPUnit_Framework_TestCase
{
	public function testEncode() {
		$el = new Sequence();
		$this->assertEquals("\x30\x0", $el->toDER());
	}
	
	public function testSingle() {
		$el = new Sequence(new NullType());
		$this->assertEquals("\x30\x2\x5\x0", $el->toDER());
	}
	
	public function testThree() {
		$el = new Sequence(new NullType(), new NullType(), new NullType());
		$this->assertEquals("\x30\x6" . str_repeat("\x5\x0", 3), $el->toDER());
	}
	
	public function testNested() {
		$el = new Sequence(new Sequence(new NullType()));
		$this->assertEquals("\x30\x4\x30\x2\x5\x0", $el->toDER());
	}
}
