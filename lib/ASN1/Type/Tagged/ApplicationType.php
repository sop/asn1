<?php

declare(strict_types = 1);

namespace ASN1\Type\Tagged;

use ASN1\Component\Identifier;
use ASN1\Component\Length;
use ASN1\Feature\ElementBase;

/**
 * Intermediate class to store DER data of an application specific type.
 */
class ApplicationType extends DERTaggedType
{
    /**
     *
     * {@inheritdoc}
     */
    protected static function _decodeFromDER(Identifier $identifier, string $data,
        int &$offset): ElementBase
    {
        $idx = $offset;
        $type = new self($identifier, $data, $idx);
        $length = Length::expectFromDER($data, $idx)->intLength();
        $offset = $idx + $length;
        return $type;
    }
}
