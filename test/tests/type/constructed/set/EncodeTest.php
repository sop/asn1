<?php

use ASN1\Type\Constructed\Set;
use ASN1\Type\Primitive\NullType;
use ASN1\Type\Primitive\PrintableString;
use ASN1\Type\Tagged\ExplicitlyTaggedType;
use ASN1\Type\Tagged\ImplicitlyTaggedType;


/**
 * @group encode
 * @group structure
 * @group set
 */
class SetEncodeTest extends PHPUnit_Framework_TestCase
{
	public function testEncode() {
		$el = new Set();
		$this->assertEquals("\x31\x0", $el->toDER());
	}
	
	public function testSetSort() {
		$set = new Set(new ImplicitlyTaggedType(1, new NullType()), 
			new ImplicitlyTaggedType(2, new NullType()), 
			new ImplicitlyTaggedType(0, new NullType()));
		$this->assertEquals("\x31\x6\x80\x0\x81\x0\x82\x0", 
			$set->sortedSet()
				->toDER());
	}
	
	public function testSetSortClasses() {
		$set = new Set(new ExplicitlyTaggedType(5, new NullType()), 
			new ImplicitlyTaggedType(6, new NullType()), new NullType());
		$this->assertEquals("\x31\x8\x05\x0\xa5\x2\x05\x0\x86\x0", 
			$set->sortedSet()
				->toDER());
	}
	
	public function testSetOfSort() {
		$set = new Set(new PrintableString("B"), new PrintableString("C"), 
			new PrintableString("A"));
		$this->assertEquals("\x31\x9" . "\x13\x01A" . "\x13\x01B" . "\x13\x01C", 
			$set->sortedSetOf()
				->toDER());
	}
}
