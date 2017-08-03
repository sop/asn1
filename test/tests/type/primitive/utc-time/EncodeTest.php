<?php

use ASN1\Type\Primitive\UTCTime;

/**
 * @group encode
 * @group utc-time
 */
class UTCTimeEncodeTest extends PHPUnit_Framework_TestCase
{
    public function testEncode()
    {
        $el = new UTCTime(new \DateTimeImmutable("Mon Jan 2 15:04:05 MST 2006"));
        $this->assertEquals("\x17\x0d" . "060102220405Z", $el->toDER());
    }
}
