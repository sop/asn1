<?php
declare(strict_types = 1);

use ASN1\Element;
use ASN1\Type\Tagged\ContextSpecificType;
use PHPUnit\Framework\TestCase;

/**
 *
 * @group type
 * @group context-specific
 */
class ContextSpecificTypeTest extends TestCase
{
    public function testExplicitType()
    {
        $el = Element::fromDER(hex2bin('a1020500'));
        $this->assertInstanceOf(ContextSpecificType::class, $el);
    }
    public function testImplicitType()
    {
        $el = Element::fromDER(hex2bin('8100'));
        $this->assertInstanceOf(ContextSpecificType::class, $el);
    }
}
