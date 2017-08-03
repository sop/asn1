<?php

use ASN1\Type\Primitive\BitString;

/**
 * @group encode
 * @group bit-string
 */
class BitStringEncodeTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider withoutTrailingZeroesProvider
     */
    public function testWithoutTrailingZeroes($bits, $expected)
    {
        $bs = new BitString($bits);
        $this->assertEquals($expected,
            $bs->withoutTrailingZeroes()
                ->toDER());
    }
    
    public function withoutTrailingZeroesProvider()
    {
        return array(
            /* @formatter:off */
            ["", "\x3\x1\x0"],
            ["\x00", "\x3\x1\x0"],
            ["\x80", "\x3\x2\x7\x80"],
            ["\xf0", "\x3\x2\x4\xf0"],
            ["\xfe", "\x3\x2\x1\xfe"],
            ["\xff", "\x3\x2\x0\xff"],
            ["\xff\xff\xf0", "\x3\x4\x4\xff\xff\xf0"],
            ["\xff\xf0\x00", "\x3\x3\x4\xff\xf0"],
            ["\xf0\x00\x00", "\x3\x2\x4\xf0"],
            ["\x00\x00\x00", "\x3\x1\x0"],
            ["\x00\x00\x02", "\x3\x4\x1\x0\x0\x02"],
            ["\x00\x02\x00", "\x3\x3\x1\x0\x02"],
            ["\x00\x01\x00", "\x3\x3\x0\x0\x01"],
            ["\x00\x80\x00", "\x3\x3\x7\x0\x80"]
            /* @formatter:on */
        );
    }
}
