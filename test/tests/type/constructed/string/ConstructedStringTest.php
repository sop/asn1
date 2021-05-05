<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Element;
use Sop\ASN1\Type\Constructed\ConstructedString;
use Sop\ASN1\Type\Primitive\BitString;
use Sop\ASN1\Type\Primitive\BMPString;
use Sop\ASN1\Type\Primitive\CharacterString;
use Sop\ASN1\Type\Primitive\GeneralizedTime;
use Sop\ASN1\Type\Primitive\GeneralString;
use Sop\ASN1\Type\Primitive\GraphicString;
use Sop\ASN1\Type\Primitive\IA5String;
use Sop\ASN1\Type\Primitive\NullType;
use Sop\ASN1\Type\Primitive\NumericString;
use Sop\ASN1\Type\Primitive\ObjectDescriptor;
use Sop\ASN1\Type\Primitive\OctetString;
use Sop\ASN1\Type\Primitive\PrintableString;
use Sop\ASN1\Type\Primitive\T61String;
use Sop\ASN1\Type\Primitive\UniversalString;
use Sop\ASN1\Type\Primitive\UTCTime;
use Sop\ASN1\Type\Primitive\UTF8String;
use Sop\ASN1\Type\Primitive\VideotexString;
use Sop\ASN1\Type\Primitive\VisibleString;
use Sop\ASN1\Type\StringType;
use Sop\ASN1\Type\UnspecifiedType;

/**
 * @group structure
 * @group string
 *
 * @internal
 */
class ConstructedStringTest extends TestCase
{
    public function testCreate()
    {
        $cs = ConstructedString::createWithTag(
            Element::TYPE_OCTET_STRING,
            new OctetString('Hello'),
            new OctetString('World')
            )->withIndefiniteLength();
        $this->assertInstanceOf(ConstructedString::class, $cs);
        return $cs;
    }

    /**
     * @depends testCreate
     */
    public function testTag(Element $el)
    {
        $this->assertEquals(Element::TYPE_OCTET_STRING, $el->tag());
    }

    /**
     * @depends testCreate
     */
    public function testEncode(Element $el): string
    {
        $der = $el->toDER();
        $this->assertIsString($der);
        return $der;
    }

    /**
     * @depends testEncode
     */
    public function testDecode(string $data): ConstructedString
    {
        $el = ConstructedString::fromDER($data);
        $this->assertInstanceOf(ConstructedString::class, $el);
        return $el;
    }

    /**
     * @depends testCreate
     * @depends testDecode
     */
    public function testRecoded(Element $ref, Element $el)
    {
        $this->assertEquals($ref, $el);
    }

    /**
     * @depends testCreate
     */
    public function testStrings(ConstructedString $cs)
    {
        $this->assertEquals(['Hello', 'World'], $cs->strings());
    }

    /**
     * @depends testCreate
     */
    public function testStringable(ConstructedString $cs)
    {
        $this->assertEquals('HelloWorld', $cs->string());
        $this->assertEquals('HelloWorld', strval($cs));
    }

    /**
     * @depends testCreate
     */
    public function testIsPseudoType(ConstructedString $cs)
    {
        $this->assertTrue($cs->isType(Element::TYPE_CONSTRUCTED_STRING));
    }

    /**
     * @depends testCreate
     */
    public function testUnspecified(ConstructedString $cs)
    {
        $ut = new UnspecifiedType($cs);
        $this->assertInstanceOf(ConstructedString::class, $ut->asConstructedString());
    }

    public function testUnspecifiedFail()
    {
        $ut = new UnspecifiedType(new NullType());
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage(
            'Constructed String expected, got primitive NULL');
        $ut->asConstructedString();
    }

    public function testCreateFromElements()
    {
        $cs = ConstructedString::create(new OctetString('Hello'),
            new OctetString('World'));
        $this->assertInstanceOf(ConstructedString::class, $cs);
        return $cs;
    }

    /**
     * @depends testCreateFromElements
     */
    public function testFromElementsTag(ConstructedString $cs)
    {
        $this->assertEquals(Element::TYPE_OCTET_STRING, $cs->tag());
    }

    public function testCreateNoElementsFail()
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage(
            'No elements, unable to determine type tag');
        ConstructedString::create();
    }

    public function testCreateMixedElementsFail()
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage(
            'All elements in constructed string must have the same type');
        ConstructedString::create(new OctetString('Hello'),
            new BitString('World'));
    }

    /**
     * @dataProvider provideStringType
     */
    public function testStringTypeAndConcatenate(StringType $el)
    {
        $str = $el->string();
        $cs = ConstructedString::create($el, $el)->withIndefiniteLength();
        $der = $cs->toDER();
        $ut = ConstructedString::fromDER($der)->asUnspecified();
        $s = $ut->asString();
        $this->assertInstanceOf(StringType::class, $s);
        $this->assertEquals("{$str}{$str}", $s->string());
    }

    public function provideStringType()
    {
        static $str = 'test';
        return [
            [new BitString($str)],
            [new BMPString($str)],
            [new CharacterString($str)],
            [new GeneralString($str)],
            [new GraphicString($str)],
            [new IA5String($str)],
            [new NumericString('1234')],
            [new ObjectDescriptor($str)],
            [new OctetString($str)],
            [new PrintableString($str)],
            [new T61String($str)],
            [new UniversalString($str)],
            [new UTF8String($str)],
            [new VideotexString($str)],
            [new VisibleString($str)],
            [GeneralizedTime::fromString('now')],
            [UTCTime::fromString('now')],
        ];
    }
}
