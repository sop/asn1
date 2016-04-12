<?php

use ASN1\Type\Structure;
use ASN1\Type\Constructed\Set;


/**
 * @group decode
 */
class StructureDecodeTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Test too short length
	 * @expectedException ASN1\Exception\DecodeException
	 */
	public function testTooShort() {
		Structure::fromDER("\x30\x1\x5\x0");
	}
	
	/**
	 * Test too long length
	 * @expectedException ASN1\Exception\DecodeException
	 */
	public function testTooLong() {
		Structure::fromDER("\x30\x3\x5\x0");
	}
	
	/**
	 * Test when structure doesn't have constructed flag
	 * @expectedException ASN1\Exception\DecodeException
	 */
	public function testNotConstructed() {
		Structure::fromDER("\x10\x0");
	}
	
	public function testImplicitlyTaggedExists() {
		// null, tag 0, null
		$set = Set::fromDER("\x31\x6\x5\x0\x80\x0\x5\x0");
		$this->assertTrue($set->hasTagged(0));
	}
	
	public function testImplicitlyTaggedFetch() {
		// null, tag 1, null
		$set = Set::fromDER("\x31\x6\x5\x0\x81\x0\x5\x0");
		$this->assertInstanceOf('ASN1\Type\Tagged\DERTaggedType', 
			$set->getTagged(1));
	}
	
	public function testExplicitlyTaggedExists() {
		// null, tag 0 (null), null
		$set = Set::fromDER("\x31\x8\x5\x0\xa0\x2\x5\x0\x5\x0");
		$this->assertTrue($set->hasTagged(0));
	}
	
	public function testExplicitlyTaggedFetch() {
		// null, tag 1 (null), null
		$set = Set::fromDER("\x31\x8\x5\x0\xa1\x2\x5\x0\x5\x0");
		$this->assertInstanceOf('ASN1\Type\Tagged\DERTaggedType', 
			$set->getTagged(1));
		$this->assertInstanceOf('ASN1\Type\Primitive\NullType', 
			$set->getTagged(1)
				->explicit());
	}
	
	/**
	 * @expectedException OutOfBoundsException
	 */
	public function testInvalidTag() {
		// null, tag 0, null
		$set = Set::fromDER("\x31\x6\x5\x0\x80\x0\x5\x0");
		$set->getTagged(1);
	}
}
