<?php

namespace ASN1\Type\Primitive;

use ASN1\Component\Identifier;
use ASN1\Component\Length;
use ASN1\Exception\DecodeException;
use ASN1\Type\PrimitiveType;
use ASN1\Type\TimeType;
use ASN1\Type\UniversalClass;


/**
 * Implements <i>GeneralizedTime</i> type.
 */
class GeneralizedTime extends TimeType
{
	use UniversalClass;
	use PrimitiveType;
	
	/**
	 * Regular expression to parse date.
	 *
	 * @var string
	 */
	const REGEX = /* @formatter:off */ '#^' .
		'(\d\d\d\d)' . /* YYYY */
		'(\d\d)' . /* MM */
		'(\d\d)' . /* DD */
		'(\d\d)' . /* hh */
		'(\d\d)' . /* mm */
		'(\d\d)' . /* ss */
		'(?:\.(\d+))?' . /* frac */
		'Z' . /* TZ */
		'$#' /* @formatter:on */;
	
	/**
	 * Constructor
	 *
	 * @param \DateTimeImmutable $dt
	 */
	public function __construct(\DateTimeImmutable $dt) {
		$this->_typeTag = self::TYPE_GENERALIZED_TIME;
		parent::__construct($dt);
	}
	
	protected function _encodedContentDER() {
		if (!isset($this->_formatted)) {
			$dt = $this->_dateTime->setTimezone(new \DateTimeZone("UTC"));
			$this->_formatted = $dt->format("YmdHis");
			// if fractions were used
			$frac = $dt->format("u");
			if ($frac != 0) {
				$frac = rtrim($frac, "0");
				$this->_formatted .= ".$frac";
			}
			// timezone
			$this->_formatted .= "Z";
		}
		return $this->_formatted;
	}
	
	protected static function _decodeFromDER(Identifier $identifier, $data, 
			&$offset) {
		$idx = $offset;
		$length = Length::expectFromDER($data, $idx);
		$str = substr($data, $idx, $length->length());
		$idx += $length->length();
		if (!preg_match(self::REGEX, $str, $match)) {
			throw new DecodeException("Invalid GeneralizedTime format.");
		}
		list(, $year, $month, $day, $hour, $minute, $second) = $match;
		if (isset($match[7])) {
			$frac = $match[7];
			// DER restricts trailing zeroes in fractional seconds component
			if ($frac[strlen($frac) - 1] === '0') {
				throw new DecodeException(
					"Fractional seconds must omit trailing zeroes.");
			}
			$frac = (int) $frac;
		} else {
			$frac = 0;
		}
		$time = $year . $month . $day . $hour . $minute . $second . "." . $frac .
			 "UTC";
		$dt = \DateTimeImmutable::createFromFormat("!YmdHis.uT", $time, 
			new \DateTimeZone("UTC"));
		if (!$dt) {
			throw new DecodeException("Failed to decode GeneralizedTime.");
		}
		$offset = $idx;
		return new self($dt);
	}
}
