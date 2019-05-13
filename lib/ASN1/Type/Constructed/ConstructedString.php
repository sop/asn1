<?php

declare(strict_types = 1);

namespace ASN1\Type\Constructed;

use ASN1\Component\Identifier;
use ASN1\Element;
use ASN1\Feature\ElementBase;
use ASN1\Type\PrimitiveString;
use ASN1\Type\Structure;

/**
 * Implements constructed type of simple strings.
 */
class ConstructedString extends Structure
{
    /**
     * Constructor.
     *
     * @internal Use create()
     *
     * @param Element ...$elements Any number of elements
     */
    public function __construct(Element ...$elements)
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
    protected static function _decodeFromDER(
        Identifier $identifier,
        string $data,
        int &$offset
    ): ElementBase {
        /** @var ConstructedString $type */
        $type = forward_static_call_array(
            [parent::class, __FUNCTION__],
            [$identifier, $data, &$offset]
        );
        $type->_typeTag = $identifier->intTag();
        return $type;
    }
}
