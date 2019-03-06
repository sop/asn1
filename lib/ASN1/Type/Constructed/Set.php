<?php
declare(strict_types = 1);

namespace ASN1\Type\Constructed;

use ASN1\Element;
use ASN1\Type\Structure;

/**
 * Implements <i>SET</i> and <i>SET OF</i> types.
 */
class Set extends Structure
{
    /**
     * Constructor.
     *
     * @param Element ...$elements Any number of elements
     */
    public function __construct(Element ...$elements)
    {
        $this->_typeTag = self::TYPE_SET;
        parent::__construct(...$elements);
    }
    
    /**
     * Sort by canonical ascending order.
     * Used for DER encoding of SET type.
     *
     * @return self
     */
    public function sortedSet(): self
    {
        $obj = clone $this;
        usort($obj->_elements,
            function (Element $a, Element $b) {
                if ($a->typeClass() != $b->typeClass()) {
                    return $a->typeClass() < $b->typeClass() ? -1 : 1;
                }
                if ($a->tag() == $b->tag()) {
                    return 0;
                }
                return $a->tag() < $b->tag() ? -1 : 1;
            });
        return $obj;
    }
    
    /**
     * Sort by encoding ascending order.
     * Used for DER encoding of SET OF type.
     *
     * @return self
     */
    public function sortedSetOf(): self
    {
        $obj = clone $this;
        usort($obj->_elements,
            function (Element $a, Element $b) {
                $a_der = $a->toDER();
                $b_der = $b->toDER();
                return strcmp($a_der, $b_der);
            });
        return $obj;
    }
}
