<?php
declare(strict_types = 1);

namespace ASN1\Type\Primitive;

use ASN1\Element;
use ASN1\Component\Identifier;
use ASN1\Component\Length;
use ASN1\Exception\DecodeException;
use ASN1\Feature\ElementBase;
use ASN1\Type\PrimitiveType;
use ASN1\Type\UniversalClass;

/**
 * Implements <i>BOOLEAN</i> type.
 */
class Boolean extends Element
{
    use UniversalClass;
    use PrimitiveType;
    
    /**
     * Value.
     *
     * @var bool
     */
    private $_bool;
    
    /**
     * Constructor.
     *
     * @param bool $bool
     */
    public function __construct(bool $bool)
    {
        $this->_typeTag = self::TYPE_BOOLEAN;
        $this->_bool = $bool;
    }
    
    /**
     * Get the value.
     *
     * @return bool
     */
    public function value(): bool
    {
        return $this->_bool;
    }
    
    /**
     *
     * {@inheritdoc}
     */
    protected function _encodedContentDER(): string
    {
        return $this->_bool ? chr(0xff) : chr(0);
    }
    
    /**
     *
     * {@inheritdoc}
     * @return self
     */
    protected static function _decodeFromDER(Identifier $identifier,
        string $data, int &$offset): ElementBase
    {
        $idx = $offset;
        Length::expectFromDER($data, $idx, 1);
        $byte = ord($data[$idx++]);
        if ($byte !== 0) {
            if ($byte != 0xff) {
                throw new DecodeException(
                    "DER encoded boolean true must have all bits set to 1.");
            }
        }
        $offset = $idx;
        return new self($byte !== 0);
    }
}
