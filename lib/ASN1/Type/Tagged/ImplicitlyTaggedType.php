<?php
declare(strict_types = 1);

namespace ASN1\Type\Tagged;

use ASN1\Element;
use ASN1\Component\Identifier;
use ASN1\Type\UnspecifiedType;

/**
 * Implements implicit tagging mode.
 *
 * Implicit tagging changes the tag of the tagged type. This changes the
 * DER encoding of the type, and hence the abstract syntax must be known when
 * decoding the data.
 */
class ImplicitlyTaggedType extends TaggedTypeWrap implements ImplicitTagging
{
    /**
     * Constructor.
     *
     * @param int $tag Tag number
     * @param Element $element Wrapped element
     * @param int $class Type class
     */
    public function __construct(int $tag, Element $element,
        int $class = Identifier::CLASS_CONTEXT_SPECIFIC)
    {
        $this->_typeTag = $tag;
        $this->_element = $element;
        $this->_class = $class;
    }
    
    /**
     *
     * @see \ASN1\Element::isConstructed()
     * @return bool
     */
    public function isConstructed(): bool
    {
        // depends on the underlying type
        return $this->_element->isConstructed();
    }
    
    /**
     *
     * @see \ASN1\Element::_encodedContentDER()
     * @return string
     */
    protected function _encodedContentDER(): string
    {
        // get only the content of the wrapped element.
        return $this->_element->_encodedContentDER();
    }
    
    /**
     *
     * {@inheritdoc}
     * @see \ASN1\Type\Tagged\ImplicitTagging::implicit()
     * @return UnspecifiedType
     */
    public function implicit(int $tag, int $class = Identifier::CLASS_UNIVERSAL): UnspecifiedType
    {
        $this->_element->expectType($tag);
        if ($this->_element->typeClass() != $class) {
            throw new \UnexpectedValueException(
                sprintf("Type class %s expected, got %s.",
                    Identifier::classToName($class),
                    Identifier::classToName($this->_element->typeClass())));
        }
        return $this->_element->asUnspecified();
    }
}
