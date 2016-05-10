<?php

use ASN1\Type\Constructed\Set;
use ASN1\Type\Primitive\NullType;


/**
 * @group structure
 * @group set
 */
class SetTest extends PHPUnit_Framework_TestCase
{
	public function testSortSame() {
		$set = new Set(new NullType(), new NullType());
		$sorted = $set->sortedSet();
		$this->assertEquals($set, $sorted);
	}
}
