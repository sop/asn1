<?php

declare(strict_types = 1);

namespace ASN1\Type\Tagged;

use ASN1\Element;
use ASN1\Type\UnspecifiedType;

/**
 * Implements explicit tagging mode.
 *
 * Explicit tagging wraps a type by prepending a tag. Underlying DER encoding
 * is not changed.
 */
class ExplicitlyTaggedType extends ContextSpecificTaggedType implements 
    ExplicitTagging
{
    /**
     * Constructor.
     *
     * @param int $tag Tag number
     * @param Element $element Wrapped element
     */
    public function __construct($tag, Element $element)
    {
        $this->_typeTag = $tag;
        $this->_element = $element;
    }
    
    /**
     *
     * @see \ASN1\Element::isConstructed()
     * @return bool
     */
    public function isConstructed(): bool
    {
        return true;
    }
    
    /**
     *
     * @see \ASN1\Element::_encodedContentDER()
     * @return string
     */
    protected function _encodedContentDER(): string
    {
        return $this->_element->toDER();
    }
    
    /**
     *
     * {@inheritdoc}
     * @see \ASN1\Type\Tagged\ExplicitTagging::explicit()
     * @return UnspecifiedType
     */
    public function explicit($expectedTag = null): UnspecifiedType
    {
        if (isset($expectedTag)) {
            $this->_element->expectType($expectedTag);
        }
        return new UnspecifiedType($this->_element);
    }
}
