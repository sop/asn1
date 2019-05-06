<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Exception\DecodeException;
use Sop\ASN1\Type\Primitive\UTCTime;

/**
 * @group decode
 * @group utc-time
 *
 * @internal
 */
class UTCTimeDecodeTest extends TestCase
{
    public function testType()
    {
        $el = UTCTime::fromDER("\x17\x0d" . '060102220405Z');
        $this->assertInstanceOf(UTCTime::class, $el);
    }

    public function testValue()
    {
        $date = strtotime('Mon Jan 2 15:04:05 MST 2006');
        $el = UTCTime::fromDER("\x17\x0d" . '060102220405Z');
        $this->assertEquals($date, $el->dateTime()
            ->getTimestamp());
    }

    public function testWithoutSeconds()
    {
        $this->expectException(DecodeException::class);
        UTCTime::fromDER("\x17\x0b" . '0601022204Z');
    }

    public function testWithTimezone()
    {
        $this->expectException(DecodeException::class);
        UTCTime::fromDER("\x17\x11" . '060102150405+0700');
    }

    public function testEmpty()
    {
        $this->expectException(DecodeException::class);
        UTCTime::fromDER("\x17\x0");
    }

    public function testInvalidFormat()
    {
        $this->expectException(DecodeException::class);
        UTCTime::fromDER("\x17\x0d" . 'o60102220405Z');
    }

    public function testNoTimezone()
    {
        $this->expectException(DecodeException::class);
        UTCTime::fromDER("\x17\x0c" . '060102220405');
    }
}
