<?php
declare(strict_types = 1);

use ASN1\Type\Structure;
use ASN1\Type\Constructed\Sequence;
use ASN1\Type\Constructed\Set;
use ASN1\Type\Primitive\NullType;
use ASN1\Type\Tagged\DERTaggedType;

/**
 *
 * @group decode
 * @group structure
 */
class StructureDecodeTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test too short length
     *
     * @expectedException ASN1\Exception\DecodeException
     */
    public function testTooShort()
    {
        Structure::fromDER("\x30\x1\x5\x0");
    }
    
    /**
     * Test too long length
     *
     * @expectedException ASN1\Exception\DecodeException
     */
    public function testTooLong()
    {
        Structure::fromDER("\x30\x3\x5\x0");
    }
    
    /**
     * Test when structure doesn't have constructed flag
     *
     * @expectedException ASN1\Exception\DecodeException
     */
    public function testNotConstructed()
    {
        Structure::fromDER("\x10\x0");
    }
    
    /**
     */
    public function testImplicitlyTaggedExists()
    {
        // null, tag 0, null
        $set = Set::fromDER("\x31\x6\x5\x0\x80\x0\x5\x0");
        $this->assertTrue($set->hasTagged(0));
    }
    
    /**
     */
    public function testImplicitlyTaggedFetch()
    {
        // null, tag 1, null
        $set = Set::fromDER("\x31\x6\x5\x0\x81\x0\x5\x0");
        $this->assertInstanceOf(DERTaggedType::class, $set->getTagged(1));
    }
    
    /**
     */
    public function testExplicitlyTaggedExists()
    {
        // null, tag 0 (null), null
        $set = Set::fromDER("\x31\x8\x5\x0\xa0\x2\x5\x0\x5\x0");
        $this->assertTrue($set->hasTagged(0));
    }
    
    /**
     */
    public function testExplicitlyTaggedFetch()
    {
        // null, tag 1 (null), null
        $set = Set::fromDER("\x31\x8\x5\x0\xa1\x2\x5\x0\x5\x0");
        $this->assertInstanceOf(DERTaggedType::class, $set->getTagged(1));
        $this->assertInstanceOf(NullType::class,
            $set->getTagged(1)
                ->expectExplicit()
                ->explicit()
                ->asNull());
    }
    
    /**
     *
     * @expectedException LogicException
     */
    public function testInvalidTag()
    {
        // null, tag 0, null
        $set = Set::fromDER("\x31\x6\x5\x0\x80\x0\x5\x0");
        $set->getTagged(1);
    }
    
    /**
     */
    public function testIndefinite()
    {
        $seq = Sequence::fromDER(hex2bin('30800201010000'));
        $this->assertInstanceOf(Sequence::class, $seq);
    }
    
    /**
     *
     * @expectedException ASN1\Exception\DecodeException
     * @expectedExceptionMessageRegExp /^Unexpected end of data while decoding indefinite length structure/
     */
    public function testIndefiniteUnexpectedEnd()
    {
        Sequence::fromDER(hex2bin('3080020101'));
    }
}
