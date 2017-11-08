<?php

declare(strict_types = 1);

namespace ASN1\Type\Tagged;

use ASN1\Element;
use ASN1\Component\Identifier;
use ASN1\Component\Length;
use ASN1\Type\TaggedType;
use ASN1\Type\UnspecifiedType;

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
     * Offset to data.
     *
     * @var int
     */
    private $_offset;
    
    /**
     * Constructor.
     *
     * @param Identifier $identifier
     * @param string $data
     * @param int $offset Offset to next byte after identifier
     */
    public function __construct(Identifier $identifier, string $data, int $offset)
    {
        $this->_identifier = $identifier;
        $this->_data = $data;
        $this->_offset = $offset;
        $this->_typeTag = $identifier->intTag();
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
        $idx = $this->_offset;
        $length = Length::expectFromDER($this->_data, $idx)->intLength();
        return substr($this->_data, $idx, $length);
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
        $idx = $this->_offset;
        Length::expectFromDER($this->_data, $idx);
        $element = Element::fromDER($this->_data, $idx);
        if (isset($expectedTag)) {
            $element->expectType($expectedTag);
        }
        return $element->asUnspecified();
    }
}
