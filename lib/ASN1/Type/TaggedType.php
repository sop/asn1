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
	 * @param int|null $expectedTag Optional outer tag expectation
	 * @throws \UnexpectedValueException If expectation fails
	 * @return ExplicitTagging
	 */
	public function expectExplicit($expectedTag = null) {
		$el = $this;
		if (!$el instanceof ExplicitTagging) {
			throw new \UnexpectedValueException(
				"Element doesn't implement explicit tagging.");
		}
		if (isset($expectedTag)) {
			$el->expectTagged($expectedTag);
		}
		return $el;
	}
	
	/**
	 * Get the wrapped inner element employing explicit tagging.
	 *
	 * @param int|null $expectedTag Optional outer tag expectation
	 * @throws \UnexpectedValueException If expectation fails
	 * @return UnspecifiedType
	 */
	public function asExplicit($expectedTag = null) {
		return $this->expectExplicit($expectedTag)->explicit();
	}
	
	/**
	 * Check whether element supports implicit tagging.
	 *
	 * @param int|null $expectedTag Optional outer tag expectation
	 * @throws \UnexpectedValueException If expectation fails
	 * @return ImplicitTagging
	 */
	public function expectImplicit($expectedTag = null) {
		$el = $this;
		if (!$el instanceof ImplicitTagging) {
			throw new \UnexpectedValueException(
				"Element doesn't implement implicit tagging.");
		}
		if (isset($expectedTag)) {
			$el->expectTagged($expectedTag);
		}
		return $el;
	}
	
	/**
	 * Get the wrapped inner element employing implicit tagging.
	 *
	 * @param int $tag Type tag of the inner element
	 * @param int|null $expectedTag Optional outer tag expectation
	 * @param int $expectedClass Optional inner type class expectation
	 * @throws \UnexpectedValueException If expectation fails
	 * @return UnspecifiedType
	 */
	public function asImplicit($tag, $expectedTag = null, 
			$expectedClass = Identifier::CLASS_UNIVERSAL) {
		return $this->expectImplicit($expectedTag)->implicit($tag, 
			$expectedClass);
	}
}
