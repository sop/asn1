<?php
declare(strict_types = 1);

namespace ASN1\Type\Constructed;

use ASN1\Element;
use ASN1\Type\Structure;

/**
 * Implements <i>SEQUENCE</i> and <i>SEQUENCE OF</i> types.
 */
class Sequence extends Structure
{
    /**
     * Constructor.
     *
     * @param Element ...$elements Any number of elements
     */
    public function __construct(Element ...$elements)
    {
        $this->_typeTag = self::TYPE_SEQUENCE;
        parent::__construct(...$elements);
    }
}
