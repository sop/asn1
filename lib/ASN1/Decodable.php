<?php

namespace ASN1;


interface Decodable
{
	/**
	 * Decode from DER
	 *
	 * @param string $data DER encoded data
	 * @param int $offset Reference to variable that contains offset into data
	 *        where to start parsing. Variable is updated to offset next to
	 *        parsed element. If null, start from offset 0.
	 * @return mixed Decoded object depending on context
	 */
	public static function fromDER($data, &$offset = null);
}
