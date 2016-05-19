<?php

namespace ASN1\Type\Primitive;

use ASN1\Type\PrimitiveString;
use ASN1\Type\UniversalClass;


/**
 * Implements <i>ObjectDescriptor</i> type.
 */
class ObjectDescriptor extends PrimitiveString
{
	use UniversalClass;
	
	/**
	 * Constructor
	 *
	 * @param string $descriptor
	 */
	public function __construct($descriptor) {
		$this->_string = $descriptor;
		$this->_typeTag = self::TYPE_OBJECT_DESCRIPTOR;
	}
	
	/**
	 * Get the object descriptor.
	 *
	 * @return string
	 */
	public function descriptor() {
		return $this->_string;
	}
}
