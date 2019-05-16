<?php
declare(strict_types = 1);

namespace ASN1\Type\Constructed;

use ASN1\Element;
use ASN1\Component\Identifier;
use ASN1\Feature\ElementBase;
use ASN1\Feature\Stringable;
use ASN1\Type\StringType;
use ASN1\Type\Structure;

/**
 * Implements constructed type of simple strings.
 */
class ConstructedString extends Structure implements Stringable
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
     * Create from a list of string type elements
     *
     * All strings must have the same type.
     *
     * @param StringType ...$elements
     * @throws \LogicException
     * @return self
     */
    public static function create(StringType ...$elements): self
    {
        if (!count($elements)) {
            throw new \LogicException(
                'No elements, unable to determine type tag.');
        }
        $tag = $elements[0]->tag();
        foreach ($elements as $el) {
            if ($el->tag() !== $tag) {
                throw new \LogicException(
                    'All elements in constructed string must have the same type.');
            }
        }
        return self::createWithTag($tag, ...$elements);
    }
    
    /**
     * Create from strings with a given type tag.
     *
     * @param int $tag Type tag for the constructed string element
     * @param StringType ...$elements Any number of elements
     * @return self
     */
    public static function createWithTag(int $tag, StringType ...$elements)
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
        return array_map(function (StringType $el) {
            return $el->string();
        }, $this->_elements);
    }
    
    /**
     * Get the contained strings concatenated together.
     *
     * NOTE: It's unclear how bit strings with unused bits should be
     * concatentated.
     *
     * @return string
     */
    public function string(): string
    {
        return implode('', $this->strings());
    }
    
    /**
     *
     * {@inheritdoc}
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->string();
    }
    
    /**
     *
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
