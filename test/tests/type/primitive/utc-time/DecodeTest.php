<?php

use ASN1\Type\Primitive\UTCTime;

/**
 * @group decode
 * @group utc-time
 */
class UTCTimeDecodeTest extends PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $el = UTCTime::fromDER("\x17\x0d" . "060102220405Z");
        $this->assertInstanceOf(UTCTime::class, $el);
    }
    
    public function testValue()
    {
        $date = strtotime("Mon Jan 2 15:04:05 MST 2006");
        $el = UTCTime::fromDER("\x17\x0d" . "060102220405Z");
        $this->assertEquals($date, $el->dateTime()
            ->getTimestamp());
    }
    
    /**
     * @expectedException ASN1\Exception\DecodeException
     */
    public function testWithoutSeconds()
    {
        UTCTime::fromDER("\x17\x0b" . "0601022204Z");
    }
    
    /**
     * @expectedException ASN1\Exception\DecodeException
     */
    public function testWithTimezone()
    {
        UTCTime::fromDER("\x17\x11" . "060102150405+0700");
    }
    
    /**
     * @expectedException ASN1\Exception\DecodeException
     */
    public function testEmpty()
    {
        UTCTime::fromDER("\x17\x0");
    }
    
    /**
     * @expectedException ASN1\Exception\DecodeException
     */
    public function testInvalidFormat()
    {
        UTCTime::fromDER("\x17\x0d" . "o60102220405Z");
    }
    
    /**
     * @expectedException ASN1\Exception\DecodeException
     */
    public function testNoTimezone()
    {
        UTCTime::fromDER("\x17\x0c" . "060102220405");
    }
}
