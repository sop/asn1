<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Element;
use Sop\ASN1\Type\Primitive\NullType;
use Sop\ASN1\Type\Primitive\Real;
use Sop\ASN1\Type\UnspecifiedType;

/**
 * @group type
 * @group real
 *
 * @internal
 */
class RealTest extends TestCase
{
    public function testCreate()
    {
        $el = new Real('314.E-2');
        $this->assertInstanceOf(Real::class, $el);
        return $el;
    }

    /**
     * @depends testCreate
     *
     * @param Element $el
     */
    public function testTag(Element $el)
    {
        $this->assertEquals(Element::TYPE_REAL, $el->tag());
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
     * @return float
     */
    public function testDecode(string $data): Real
    {
        $el = Real::fromDER($data);
        $this->assertInstanceOf(Real::class, $el);
        return $el;
    }

    /**
     * @depends testCreate
     * @depends testDecode
     *
     * @param Element $ref
     * @param Element $el
     */
    public function testRecoded(Element $ref, Element $el)
    {
        $this->assertEquals($ref, $el);
    }

    /**
     * @dataProvider provideFromFloat
     *
     * @param float $number
     */
    public function testFromFloat($number)
    {
        $real = Real::fromFloat($number);
        $recoded = Real::fromDER($real->toDER());
        $this->assertEquals($number, $recoded->float());
    }

    public function provideFromFloat(): array
    {
        return [
            [0],
            [1],
            [-1],
            [1000000],
            [-1000000],
            [0.00001],
            [-0.00001],
            [0.1e-20],
            [-0.1e-20],
            [M_PI],
            [-M_PI],
            [M_E],
            [-M_E],
        ];
    }

    public function testInvalidFormatFail()
    {
        $this->expectException(InvalidArgumentException::class);
        new Real('fail');
    }

    public function testNR3ToDecimalInvalidFormatFail()
    {
        $real = new Real(Real::NR3_ZERO);
        $cls = new ReflectionClass($real);
        $prop = $cls->getProperty('_number');
        $prop->setAccessible(true);
        $prop->setValue($real, 'fail');
        $this->expectException(UnexpectedValueException::class);
        $real->float();
    }

    /**
     * @depends testCreate
     *
     * @param Element $el
     */
    public function testWrapped(Element $el)
    {
        $wrap = new UnspecifiedType($el);
        $this->assertInstanceOf(Real::class, $wrap->asReal());
    }

    public function testWrappedFail()
    {
        $wrap = new UnspecifiedType(new NullType());
        $this->expectException(UnexpectedValueException::class);
        $wrap->asReal();
    }
}
