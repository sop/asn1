<?php

declare(strict_types = 1);

use ASN1\Type\Primitive\BitString;
use ASN1\Util\Flags;

/**
 * @group util
 * @group flags
 */
class FlagsTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider flagsProvider
     *
     * @param number $num
     * @param int $width
     * @param string $result
     */
    public function testFlags($num, int $width, string $result)
    {
        $flags = new Flags($num, $width);
        $this->assertEquals($result, $flags->string());
    }
    
    public function flagsProvider(): array
    {
        return [ /* @formatter:off */
            [1, 0, ""],
            [1, 1, "\x80"],
            [1, 4, "\x10"],
            [1, 6, "\x04"],
            [1, 8, "\x01"],
            [1, 12, "\x00\x10"],
            [1, 16, "\x00\x01"],
            [0, 8, "\x00"],
            [0, 9, "\x00\x00"],
            [0xff, 8, "\xff"],
            [0xff, 4, "\xf0"],
            [0xff, 1, "\x80"],
            [0xffff, 1, "\x80"],
            [0xffffff, 12, "\xff\xf0"],
            [1, 128, "\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\x01"],
            ["0x80000000000000000000000000000000",
                128, "\x80\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0"],
            /* @formatter:on */
        ];
    }
    
    /**
     * @dataProvider setBitProvider
     *
     * @param int $num
     * @param int $width
     * @param int $idx
     */
    public function testSetBit(int $num, int $width, int $idx)
    {
        $flags = new Flags($num, $width);
        $this->assertTrue($flags->test($idx));
    }
    
    public function setBitProvider(): array
    {
        return [ /* @formatter:off */
            [1, 1, 0],
            [1, 4, 3],
            [1, 8, 7],
            [1, 16, 15],
            [1, 128, 127],
            [0x08, 4, 0],
            [0x80, 8, 0],
            [0x8000, 16, 0],
            [0x80, 16, 8],
            /* @formatter:on */
        ];
    }
    
    /**
     * @dataProvider unsetBitProvider
     *
     * @param int $num
     * @param int $width
     * @param int $idx
     */
    public function testUnsetBit(int $num, int $width, int $idx)
    {
        $flags = new Flags($num, $width);
        $this->assertFalse($flags->test($idx));
    }
    
    public function unsetBitProvider(): array
    {
        return [ /* @formatter:off */
            [0x7f, 8, 0],
            [0xfe, 8, 7],
            [0xff7f, 8, 0],
            [0xff7f, 12, 4],
            [0xff7f, 16, 8],
            /* @formatter:on */
        ];
    }
    
    /**
     * @dataProvider toBitStringProvider
     *
     * @param int $num
     * @param int $width
     * @param string $result
     * @param int $unused_bits
     */
    public function testToBitString(int $num, int $width, $result,
        int $unused_bits)
    {
        $flags = new Flags($num, $width);
        $bs = $flags->bitString();
        $this->assertEquals($result, $bs->string());
        $this->assertEquals($unused_bits, $bs->unusedBits());
    }
    
    public function toBitStringProvider(): array
    {
        return [ /* @formatter:off */
            [0, 0, "", 0],
            [1, 1, "\x80", 7],
            [1, 4, "\x10", 4],
            [1, 8, "\x01", 0],
            [1, 12, "\x0\x10", 4],
            [1, 16, "\x0\x01", 0],
            [0, 16, "\x0\x0", 0],
            [0x800, 12, "\x80\x0", 4],
            [0x8000, 16, "\x80\x0", 0],
            /* @formatter:on */
        ];
    }
    
    /**
     * @dataProvider fromBitStringProvider
     *
     * @param string $str
     * @param int $unused_bits
     * @param int $width
     * @param string $result
     */
    public function testFromBitString(string $str, int $unused_bits, int $width,
        string $result)
    {
        $flags = Flags::fromBitString(new BitString($str, $unused_bits), $width);
        $this->assertEquals($result, $flags->string());
    }
    
    public function fromBitStringProvider(): array
    {
        return [ /* @formatter:off */
            ["\xff", 0, 8, "\xff"],
            ["\xff", 0, 4, "\xf0"],
            ["", 0, 8, "\x00"],
            ["\xff\xff", 4, 16, "\xff\xf0"],
            ["\xff\x80", 7, 16, "\xff\x80"],
            ["\x00\x10", 4, 12, "\x00\x10"],
            ["\x00\x10", 4, 24, "\x00\x10\x00"],
            /* @formatter:on */
        ];
    }
    
    /**
     * @dataProvider numberProvider
     *
     * @param string $num
     * @param int $width
     * @param number $result
     */
    public function testNumber($num, $width, $result)
    {
        $flags = new Flags($num, $width);
        $this->assertEquals($result, $flags->number());
    }
    
    public function numberProvider(): array
    {
        return [ /* @formatter:off */
            [0xff, 8, 255],
            [0xff, 4, 15],
            [0xff, 2, 3],
            [0xff, 1, 1],
            [0, 8, 0],
            [1, 1, 1],
            [1, 4, 1],
            [1, 8, 1],
            [1, 12, 1],
            [1, 16, 1],
            [0x80, 24, 0x80],
            [0x8000, 16, 0x8000],
            ["0x80000000000000000000000000000000", 128,
                "170141183460469231731687303715884105728"],
            /* @formatter:on */
        ];
    }
    
    /**
     * @dataProvider bitStringToNumberProvider
     *
     * @param string $str
     * @param int $unused_bits
     * @param int $width
     * @param number $number
     */
    public function testBitStringToNumber($str, int $unused_bits, int $width,
        $number)
    {
        $bs = new BitString($str, $unused_bits);
        $flags = Flags::fromBitString($bs, $width);
        $this->assertEquals($number, $flags->number());
    }
    
    public function bitStringToNumberProvider(): array
    {
        return [ /* @formatter:off */
            ["\x20", 5, 9, 64],
            /* @formatter:on */
        ];
    }
    
    public function testIntNumber()
    {
        $flags = new Flags(0x80, 16);
        $this->assertSame($flags->intNumber(), 128);
    }
    
    /**
     * @expectedException OutOfBoundsException
     */
    public function testTestOOB()
    {
        $flags = new Flags(0, 8);
        $flags->test(8);
    }
}
