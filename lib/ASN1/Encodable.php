<?php

namespace ASN1;


interface Encodable
{
	/**
	 * Encode object to DER.
	 *
	 * @return string
	 */
	public function toDER();
}
