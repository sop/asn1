<?php

namespace ASN1\Type\Primitive;

use ASN1\Component\Identifier;
use ASN1\Component\Length;
use ASN1\Exception\DecodeException;
use ASN1\Type\PrimitiveType;
use ASN1\Type\TimeType;
use ASN1\Type\UniversalClass;


/**
 * Implements <i>UTCTime</i> type.
 */
class UTCTime extends TimeType
{
	use UniversalClass;
	use PrimitiveType;
	
	/**
	 * Regular expression to parse date.
	 *
	 * @var string
	 */
	const REGEX = /* @formatter:off */ '#^' .
		'(\d\d)' . /* YY */
		'(\d\d)' . /* MM */
		'(\d\d)' . /* DD */
		'(\d\d)' . /* hh */
		'(\d\d)' . /* mm */
		'(\d\d)' . /* ss */
		'Z' . /* TZ */
		'$#' /* @formatter:on */;
	
	/**
	 * Constructor
	 *
	 * @param \DateTimeImmutable $dt
	 */
	public function __construct(\DateTimeImmutable $dt) {
		$this->_typeTag = self::TYPE_UTC_TIME;
		parent::__construct($dt);
	}
	
	protected function _encodedContentDER() {
		$dt = $this->_dateTime->setTimezone(new \DateTimeZone("UTC"));
		return $dt->format("ymdHis\Z");
	}
	
	protected static function _decodeFromDER(Identifier $identifier, $data, 
			&$offset) {
		$idx = $offset;
		$length = Length::expectFromDER($data, $idx);
		$str = substr($data, $idx, $length->length());
		$idx += $length->length();
		if (!preg_match(self::REGEX, $str, $match)) {
			throw new DecodeException("Invalid UTCTime format.");
		}
		list(, $year, $month, $day, $hour, $minute, $second) = $match;
		$tz = "UTC";
		$time = $year . $month . $day . $hour . $minute . $second . $tz;
		$dt = \DateTimeImmutable::createFromFormat("!ymdHisT", $time, 
			new \DateTimeZone($tz));
		if (!$dt) {
			throw new DecodeException("Failed to decode UTCTime.");
		}
		$offset = $idx;
		return new self($dt);
	}
}
