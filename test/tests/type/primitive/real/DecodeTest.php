<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Exception\DecodeException;
use Sop\ASN1\Type\Primitive\Real;

/**
 * @group type
 * @group real
 *
 * @internal
 */
class RealDecodeTest extends TestCase
{
    public function testBinaryEncodingFail()
    {
        $data = "\x9\x2\x80\x0";
        $this->expectException(RuntimeException::class);
        Real::fromDER($data);
    }

    public function testNonNR3DecimalEncodingFail()
    {
        $data = "\x9\x02\x010";
        $this->expectException(RuntimeException::class);
        Real::fromDER($data);
    }

    public function testSpecialEncodingMultipleOctetsFail()
    {
        $data = "\x9\x02\x40\x0";
        $this->expectException(DecodeException::class);
        Real::fromDER($data);
    }

    public function testSpecialEncodingPositiveINF()
    {
        $data = "\x9\x01\x40";
        $this->expectException(RuntimeException::class);
        Real::fromDER($data);
    }

    public function testSpecialEncodingNegativeINF()
    {
        $data = "\x9\x01\x41";
        $this->expectException(RuntimeException::class);
        Real::fromDER($data);
    }

    public function testInvalidSpecialEncodingFail()
    {
        $data = "\x9\x01\x4f";
        $this->expectException(DecodeException::class);
        Real::fromDER($data);
    }

    public function testInvalidNumberFail()
    {
        $data = "\x9\x02\x03.";
        $this->expectException(DecodeException::class);
        Real::fromDER($data);
    }
}
