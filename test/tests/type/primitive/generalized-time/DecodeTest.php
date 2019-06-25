<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Exception\DecodeException;
use Sop\ASN1\Type\Primitive\GeneralizedTime;

/**
 * @group decode
 * @group generalized-time
 *
 * @internal
 */
class GeneralizedTimeDecodeTest extends TestCase
{
    public function testType()
    {
        $el = GeneralizedTime::fromDER("\x18\x15" . '20060102220405.99999Z');
        $this->assertInstanceOf(GeneralizedTime::class, $el);
    }

    public function testValue()
    {
        $date = strtotime('Mon Jan 2 15:04:05 MST 2006');
        $el = GeneralizedTime::fromDER("\x18\x0f" . '20060102220405Z');
        $this->assertEquals($date, $el->dateTime()
            ->getTimestamp());
    }

    public function testFractions()
    {
        $ts = strtotime('Mon Jan 2 15:04:05 MST 2006');
        $dt = \DateTimeImmutable::createFromFormat('U.u', "{$ts}.99999",
            new \DateTimeZone('UTC'));
        $el = GeneralizedTime::fromDER("\x18\x15" . '20060102220405.99999Z');
        $this->assertEquals($dt->format('c u'),
            $el->dateTime()
                ->format('c u'));
    }

    public function testNoFractions()
    {
        $dt = new \DateTimeImmutable('Mon Jan 2 15:04:05 MST 2006');
        $dt = $dt->setTimezone(new \DateTimeZone('UTC'));
        $el = GeneralizedTime::fromDER("\x18\x0f" . '20060102220405Z');
        $this->assertEquals($dt->format('c u'),
            $el->dateTime()
                ->format('c u'));
    }

    public function testInvalidFractions()
    {
        $this->expectException(DecodeException::class);
        $this->expectExceptionMessage('omit trailing zeroes');
        GeneralizedTime::fromDER("\x18\x12" . '20060102220405.50Z');
    }

    public function testInvalidFractions2()
    {
        $this->expectException(DecodeException::class);
        $this->expectExceptionMessage('omit trailing zeroes');
        GeneralizedTime::fromDER("\x18\x11" . '20060102220405.0Z');
    }

    public function testInvalidFractionsOnlyDot()
    {
        $this->expectException(DecodeException::class);
        $this->expectExceptionMessage('Invalid GeneralizedTime format');
        GeneralizedTime::fromDER("\x18\x10" . '20060102220405.Z');
    }

    public function testNoTimezone()
    {
        $this->expectException(DecodeException::class);
        $this->expectExceptionMessage('Invalid GeneralizedTime format');
        GeneralizedTime::fromDER("\x18\x0e" . '20060102220405');
    }

    public function testInvalidTime()
    {
        $this->expectException(DecodeException::class);
        $this->expectExceptionMessage('Failed to decode GeneralizedTime');
        GeneralizedTime::fromDER("\x18\x19" . '20060102220405.123456789Z');
    }
}
