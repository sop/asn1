<?php
declare(strict_types = 1);

namespace ASN1\Feature;

/**
 * Interface for classes that may be encoded to DER.
 */
interface Encodable
{
    /**
     * Encode object to DER.
     *
     * @return string
     */
    public function toDER(): string;
}
