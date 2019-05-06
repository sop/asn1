<?php

declare(strict_types = 1);

namespace Sop\ASN1\Type\Tagged;

use Sop\ASN1\Type\TaggedType;

/**
 * Base class to wrap inner element for tagging.
 */
abstract class TaggedTypeWrap extends TaggedType
{
    /**
     * Wrapped element.
     *
     * @var \Sop\ASN1\Element
     */
    protected $_element;

    /**
     * Type class.
     *
     * @var int
     */
    protected $_class;

    /**
     * @see \Sop\ASN1\Element::typeClass()
     *
     * @return int
     */
    public function typeClass(): int
    {
        return $this->_class;
    }
}
