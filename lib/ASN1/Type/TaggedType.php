<?php

namespace ASN1\Type;

use ASN1\Component\Identifier;
use ASN1\Component\Length;
use ASN1\Element;
use ASN1\Type\Tagged\DERTaggedType;
use ASN1\Type\Tagged\ExplicitTagging;
use ASN1\Type\Tagged\ImplicitTagging;


/**
 * Base class for context-specific types.
 */
abstract class TaggedType extends Element
{
	protected static function _decodeFromDER(Identifier $identifier, $data, 
			&$offset) {
		$idx = $offset;
		$type = new DERTaggedType($identifier, $data, $idx);
		$length = Length::expectFromDER($data, $idx);
		$offset = $idx + $length->length();
		return $type;
	}
	
	/**
	 * Check whether element supports explicit tagging.
	 *
	 * @param int|null $expectedTag Optionally checked tag
	 * @throws \UnexpectedValueException
	 * @return self
	 */
	public function expectExplicit($expectedTag = null) {
		if (!($this instanceof ExplicitTagging)) {
			throw new \UnexpectedValueException(
				"Element doesn't implement explicit tagging.");
		}
		if (isset($expectedTag)) {
			$this->expectTagged($expectedTag);
		}
		return $this;
	}
	
	/**
	 * Check whether element supports implicit tagging.
	 *
	 * @param int|null $expectedTag
	 * @throws \UnexpectedValueException
	 * @return self
	 */
	public function expectImplicit($expectedTag = null) {
		if (!($this instanceof ImplicitTagging)) {
			throw new \UnexpectedValueException(
				"Element doesn't implement implicit tagging.");
		}
		if (isset($expectedTag)) {
			$this->expectTagged($expectedTag);
		}
		return $this;
	}
}
