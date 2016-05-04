<?php

namespace ASN1;


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
	public function toDER();
}
