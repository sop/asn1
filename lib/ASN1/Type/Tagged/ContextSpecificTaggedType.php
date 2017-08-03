<?php

namespace ASN1\Type\Tagged;

use ASN1\Component\Identifier;
use ASN1\Type\TaggedType;

/**
 * Base class for context-specific elements.
 */
abstract class ContextSpecificTaggedType extends TaggedType
{
    /**
     * Wrapped element.
     *
     * @var \ASN1\Element $_element
     */
    protected $_element;
    
    /**
     *
     * @see \ASN1\Element::typeClass()
     * @return int
     */
    public function typeClass()
    {
        return Identifier::CLASS_CONTEXT_SPECIFIC;
    }
}
