<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Type\Primitive\GeneralizedTime;
use Sop\ASN1\Type\TimeType;
use Sop\ASN1\Type\UnspecifiedType;

/**
 * @group type
 * @group time
 *
 * @internal
 */
class TimeTypeTest extends TestCase
{
    const VALUE = 'Mon Jan 2 15:04:05 MST 2006';

    public function testFromString()
    {
        $el = GeneralizedTime::fromString(self::VALUE);
        $this->assertInstanceOf(TimeType::class, $el);
        return $el;
    }

    public function testFromStringWithTz()
    {
        $el = GeneralizedTime::fromString(self::VALUE, 'Europe/Helsinki');
        $this->assertInstanceOf(TimeType::class, $el);
    }

    public function testFromInvalidStringFail()
    {
        $this->expectException(\RuntimeException::class);
        GeneralizedTime::fromString('fail');
    }

    public function testFromStringWithInvalidTzFail()
    {
        $this->expectException(\RuntimeException::class);
        GeneralizedTime::fromString(self::VALUE, 'nope');
    }

    /**
     * @depends testFromString
     *
     * @param TimeType $time
     */
    public function testWrapped(TimeType $time)
    {
        $wrap = new UnspecifiedType($time->asElement());
        $this->assertInstanceOf(TimeType::class, $wrap->asTime());
    }
}
