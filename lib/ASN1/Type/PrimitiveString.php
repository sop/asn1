<?php
declare(strict_types = 1);

namespace ASN1\Type;

use ASN1\Component\Identifier;
use ASN1\Component\Length;
use ASN1\Element;
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
            $length = Length::expectFromDER($data, $idx);

            /*
             * A primitive string is not a constructed type by definition.
             * Alas, if it is encoded as constructed type (0x24), we expect that
             * some primitive OCTET_STRINGs follow. So loop over them, create a
             * concatenated big string and return it.
             */
            if ($length->isIndefinite()) {
                $str = '';
                do {
                    $offset  = $idx;
                    $element = Element::fromDER($data, $offset);
                    if ($element instanceof PrimitiveString) {
                        $str .= $element->string();
                        $idx = $offset;
                    }
                } while ($element instanceof PrimitiveString);
                return new static($str);
            }
        } else {
            $length = Length::expectFromDER($data, $idx)->intLength();
        }
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
