<?php

declare(strict_types = 1);

namespace Sop\ASN1\Type\Constructed;

use Sop\ASN1\Component\Identifier;
use Sop\ASN1\Element;
use Sop\ASN1\Feature\ElementBase;
use Sop\ASN1\Type\PrimitiveString;
use Sop\ASN1\Type\Structure;

/**
 * Implements constructed type of simple strings.
 */
class ConstructedString extends Structure
{
    /**
     * Constructor.
     *
     * @param Element ...$elements Any number of elements
     */
    protected function __construct(Element ...$elements)
    {
        parent::__construct(...$elements);
    }

    /**
     * Create constructed string.
     *
     * @param int             $tag         Type tag
     * @param PrimitiveString ...$elements Any number of elements
     */
    public static function create(int $tag, PrimitiveString ...$elements)
    {
        $el = new self(...$elements);
        $el->_typeTag = $tag;
        return $el;
    }

    /**
     * Get a list of strings in this structure.
     *
     * @return string[]
     */
    public function strings(): array
    {
        return array_map(function (PrimitiveString $el) {
            return $el->string();
        }, $this->_elements);
    }

    /**
     * Get the contained strings concatenated together.
     *
     * @return string
     */
    public function concatenated(): string
    {
        return implode('', $this->strings());
    }

    /**
     * {@inheritdoc}
     *
     * @return self
     */
    protected static function _decodeFromDER(Identifier $identifier,
        string $data, int &$offset): ElementBase
    {
        /** @var ConstructedString $type */
        $type = forward_static_call_array([parent::class, __FUNCTION__],
            [$identifier, $data, &$offset]);
        $type->_typeTag = $identifier->intTag();
        return $type;
    }
}
