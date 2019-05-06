<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Element;
use Sop\ASN1\Type\Primitive\NullType;
use Sop\ASN1\Type\Primitive\UTCTime;
use Sop\ASN1\Type\TimeType;
use Sop\ASN1\Type\UnspecifiedType;

/**
 * @group type
 * @group utc-time
 *
 * @internal
 */
class UTCTimeTest extends TestCase
{
    public function testCreate()
    {
        $el = UTCTime::fromString('Mon Jan 2 15:04:05 MST 2006');
        $this->assertInstanceOf(UTCTime::class, $el);
        return $el;
    }

    /**
     * @depends testCreate
     *
     * @param Element $el
     */
    public function testTag(Element $el)
    {
        $this->assertEquals(Element::TYPE_UTC_TIME, $el->tag());
    }

    /**
     * @depends testCreate
     *
     * @param Element $el
     *
     * @return string
     */
    public function testEncode(Element $el): string
    {
        $der = $el->toDER();
        $this->assertIsString($der);
        return $der;
    }

    /**
     * @depends testEncode
     *
     * @param string $data
     *
     * @return UTCTime
     */
    public function testDecode(string $data): UTCTime
    {
        $el = UTCTime::fromDER($data);
        $this->assertInstanceOf(UTCTime::class, $el);
        return $el;
    }

    /**
     * @depends testCreate
     * @depends testDecode
     *
     * @param TimeType $ref
     * @param TimeType $el
     */
    public function testRecoded(TimeType $ref, TimeType $el)
    {
        $this->assertEquals($ref->dateTime()
            ->getTimestamp(), $el->dateTime()
            ->getTimestamp());
    }

    /**
     * @depends testCreate
     *
     * @param Element $el
     */
    public function testWrapped(Element $el)
    {
        $wrap = new UnspecifiedType($el);
        $this->assertInstanceOf(UTCTime::class, $wrap->asUTCTime());
    }

    public function testWrappedFail()
    {
        $wrap = new UnspecifiedType(new NullType());
        $this->expectException(UnexpectedValueException::class);
        $wrap->asUTCTime();
    }
}
