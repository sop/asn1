<?php
declare(strict_types = 1);

namespace ASN1\Type\Tagged;

use ASN1\Element;
use ASN1\Component\Identifier;
use ASN1\Type\TaggedType;
use ASN1\Type\UnspecifiedType;
use ASN1\Feature\ElementBase;
use ASN1\Component\Length;
use ASN1\Exception\DecodeException;

/**
 * Intermediate class to store tagged DER data.
 *
 * <code>implicit($tag)</code> or <code>explicit()</code> method is used
 * to decode the actual element, which is only known by the abstract syntax
 * of data structure.
 *
 * May be encoded back to complete DER encoding.
 */
class DERTaggedType extends TaggedType implements 
    ExplicitTagging,
    ImplicitTagging
{
    /**
     * Identifier.
     *
     * @var Identifier
     */
    private $_identifier;
    
    /**
     * DER data.
     *
     * @var string
     */
    private $_data;
    
    /**
     * Offset to next byte after identifier.
     *
     * @var int
     */
    private $_offset;
    
    /**
     * Offset to content.
     *
     * @var int
     */
    private $_valueOffset;
    
    /**
     * Length of the content.
     *
     * @var int
     */
    private $_valueLength;
    
    /**
     * Constructor.
     *
     * @param Identifier $identifier Pre-parsed identifier
     * @param string $data DER data
     * @param int $offset Offset to next byte after identifier
     * @param int $value_offset Offset to content
     * @param int $value_length Content length
     */
    public function __construct(Identifier $identifier, string $data,
        int $offset, int $value_offset, int $value_length,
        bool $indefinite_length)
    {
        $this->_identifier = $identifier;
        $this->_data = $data;
        $this->_offset = $offset;
        $this->_valueOffset = $value_offset;
        $this->_valueLength = $value_length;
        $this->_indefiniteLength = $indefinite_length;
        $this->_typeTag = $identifier->intTag();
    }
    
    /**
     *
     * {@inheritdoc}
     */
    protected static function _decodeFromDER(Identifier $identifier,
        string $data, int &$offset): ElementBase
    {
        $idx = $offset;
        $length = Length::expectFromDER($data, $idx);
        // offset to inner value
        $value_offset = $idx;
        if ($length->isIndefinite()) {
            if ($identifier->isPrimitive()) {
                throw new DecodeException(
                    'Primitive type with indefinite length is not supported.');
            }
            while (!Element::fromDER($data, $idx)->isType(self::TYPE_EOC));
            // EOC consists of two octets.
            $value_length = $idx - $value_offset - 2;
        } else {
            $value_length = $length->intLength();
            $idx += $value_length;
        }
        // late static binding since ApplicationType and PrivateType extend this class
        $type = new static($identifier, $data, $offset, $value_offset,
            $value_length, $length->isIndefinite());
        $offset = $idx;
        return $type;
    }
    
    /**
     *
     * @see \ASN1\Element::typeClass()
     * @return int
     */
    public function typeClass(): int
    {
        return $this->_identifier->typeClass();
    }
    
    /**
     *
     * @see \ASN1\Element::isConstructed()
     * @return bool
     */
    public function isConstructed(): bool
    {
        return $this->_identifier->isConstructed();
    }
    
    /**
     *
     * @see \ASN1\Element::_encodedContentDER()
     * @return string
     */
    protected function _encodedContentDER(): string
    {
        return substr($this->_data, $this->_valueOffset, $this->_valueLength);
    }
    
    /**
     *
     * {@inheritdoc}
     * @see \ASN1\Type\Tagged\ImplicitTagging::implicit()
     * @return UnspecifiedType
     */
    public function implicit(int $tag, int $class = Identifier::CLASS_UNIVERSAL): UnspecifiedType
    {
        $identifier = $this->_identifier->withClass($class)->withTag($tag);
        $cls = self::_determineImplClass($identifier);
        $idx = $this->_offset;
        /** @var \ASN1\Feature\ElementBase $element */
        $element = $cls::_decodeFromDER($identifier, $this->_data, $idx);
        return $element->asUnspecified();
    }
    
    /**
     *
     * @see \ASN1\Type\Tagged\ExplicitTagging::explicit()
     * @return UnspecifiedType
     */
    public function explicit($expectedTag = null): UnspecifiedType
    {
        $idx = $this->_valueOffset;
        $element = Element::fromDER($this->_data, $idx);
        if (isset($expectedTag)) {
            $element->expectType($expectedTag);
        }
        return $element->asUnspecified();
    }
}
