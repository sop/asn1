<?php
declare(strict_types = 1);

namespace ASN1\Type;

use ASN1\Component\Identifier;
use ASN1\Component\Length;
use ASN1\Exception\DecodeException;
use ASN1\Feature\ElementBase;

/**
 * Base class for primitive strings.
 */
abstract class PrimitiveString extends StringType
{
    use PrimitiveType;
    
    /**
     *
     * @see \ASN1\Element::_encodedContentDER()
     * @return string
     */
    protected function _encodedContentDER(): string
    {
        return $this->_string;
    }
    
    /**
     *
     * {@inheritdoc}
     * @see \ASN1\Element::_decodeFromDER()
     * @return self
     */
    protected static function _decodeFromDER(Identifier $identifier,
        string $data, int &$offset): ElementBase
    {
        $idx = $offset;
        if (!$identifier->isPrimitive()) {
            throw new DecodeException("DER encoded string must be primitive.");
        }
        $length = Length::expectFromDER($data, $idx)->intLength();
        $str = $length ? substr($data, $idx, $length) : "";
        // substr should never return false, since length is
        // checked by Length::expectFromDER.
        assert(is_string($str), new DecodeException("substr"));
        $offset = $idx + $length;
        try {
            return new static($str);
        } catch (\InvalidArgumentException $e) {
            throw new DecodeException($e->getMessage(), 0, $e);
        }
    }
}
