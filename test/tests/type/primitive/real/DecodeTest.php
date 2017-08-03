<?php

use ASN1\Type\Primitive\Real;

/**
 * @group type
 * @group real
 */
class RealDecodeTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException RuntimeException
     */
    public function testBinaryEncodingFail()
    {
        $data = "\x9\x2\x80\x0";
        Real::fromDER($data);
    }
    
    /**
     * @expectedException RuntimeException
     */
    public function testNonNR3DecimalEncodingFail()
    {
        $data = "\x9\x02\x010";
        Real::fromDER($data);
    }
    
    /**
     * @expectedException ASN1\Exception\DecodeException
     */
    public function testSpecialEncodingMultipleOctetsFail()
    {
        $data = "\x9\x02\x40\x0";
        Real::fromDER($data);
    }
    
    /**
     * @expectedException RuntimeException
     */
    public function testSpecialEncodingPositiveINF()
    {
        $data = "\x9\x01\x40";
        Real::fromDER($data);
    }
    
    /**
     * @expectedException RuntimeException
     */
    public function testSpecialEncodingNegativeINF()
    {
        $data = "\x9\x01\x41";
        Real::fromDER($data);
    }
    
    /**
     * @expectedException ASN1\Exception\DecodeException
     */
    public function testInvalidSpecialEncodingFail()
    {
        $data = "\x9\x01\x4f";
        Real::fromDER($data);
    }
    
    /**
     * @expectedException ASN1\Exception\DecodeException
     */
    public function testInvalidNumberFail()
    {
        $data = "\x9\x02\x03.";
        Real::fromDER($data);
    }
}
