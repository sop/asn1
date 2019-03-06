<?php
declare(strict_types = 1);

namespace ASN1\Type\Tagged;

use ASN1\Type\TaggedType;

/**
 * Base class to wrap inner element for tagging.
 */
abstract class TaggedTypeWrap extends TaggedType
{
    /**
     * Wrapped element.
     *
     * @var \ASN1\Element $_element
     */
    protected $_element;
    
    /**
     * Type class.
     *
     * @var int
     */
    protected $_class;
    
    /**
     *
     * @see \ASN1\Element::typeClass()
     * @return int
     */
    public function typeClass(): int
    {
        return $this->_class;
    }
}
