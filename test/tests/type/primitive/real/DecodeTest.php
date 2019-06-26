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
    public function testReservedBinaryEncodingFail()
    {
        $this->expectException(DecodeException::class);
        $this->expectExceptionMessage(
            'Reserved REAL binary encoding base not supported');
        Real::fromDER(hex2bin('0902B000'));
    }

    public function testBinaryEncodingExponentLengthUnexpectedEnd()
    {
        $this->expectException(DecodeException::class);
        $this->expectExceptionMessage(
            'Unexpected end of data while decoding REAL exponent length');
        Real::fromDER(hex2bin('090183'));
    }

    public function testBinaryEncodingExponentUnexpectedEnd()
    {
        $this->expectException(DecodeException::class);
        $this->expectExceptionMessage(
            'Unexpected end of data while decoding REAL exponent');
        Real::fromDER(hex2bin('090180'));
    }

    public function testBinaryEncodingMantissaUnexpectedEnd()
    {
        $this->expectException(DecodeException::class);
        $this->expectExceptionMessage(
            'Unexpected end of data while decoding REAL mantissa');
        Real::fromDER(hex2bin('09028000'));
    }

    public function testDecimalEncodingUnsupportedForm()
    {
        $this->expectException(DecodeException::class);
        $this->expectExceptionMessage('Unsupported decimal encoding form');
        Real::fromDER(hex2bin('09020400'));
    }

    public function testSpecialEncodingTooLong()
    {
        $this->expectException(DecodeException::class);
        $this->expectExceptionMessage(
            'SpecialRealValue must have one content octet');
        Real::fromDER(hex2bin('09024000'));
    }

    public function testSpecialEncodingInvalid()
    {
        $this->expectException(DecodeException::class);
        $this->expectExceptionMessage('Invalid SpecialRealValue encoding');
        Real::fromDER(hex2bin('090142'));
    }

    public function testLongExponent()
    {
        $real = Real::fromDER(hex2bin('090783044000000001'));
        $this->assertEquals('1073741824', $real->exponent()->base10());
    }
}
