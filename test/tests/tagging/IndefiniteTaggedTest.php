<?php
declare(strict_types = 1);

use ASN1\Type\TaggedType;
use ASN1\Type\Tagged\DERTaggedType;

/**
 *
 * @group tagging
 * @group indefinite
 */
class IndefiniteTaggedTest extends PHPUnit_Framework_TestCase
{
    /**
     */
    public function testDecodeIndefinite()
    {
        $el = TaggedType::fromDER(hex2bin('a0800201010000'));
        $this->assertInstanceOf(DERTaggedType::class, $el);
        return $el;
    }
    
    /**
     *
     * @depends testDecodeIndefinite
     * @param TaggedType $el
     */
    public function testEncodeIndefinite(TaggedType $el)
    {
        $der = $el->toDER();
        $this->assertEquals(hex2bin('a0800201010000'), $der);
    }
    
    /**
     *
     * @expectedException ASN1\Exception\DecodeException
     * @expectedExceptionMessageRegExp /^Primitive type/
     */
    public function testPrimitiveFail()
    {
        TaggedType::fromDER(hex2bin('80800201010000'));
    }
}
