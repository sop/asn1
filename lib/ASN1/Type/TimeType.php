<?php

namespace ASN1\Type;

use ASN1\Element;


/**
 * Base class for all types representing a point in time.
 */
abstract class TimeType extends Element
{
	/**
	 * Date and time.
	 *
	 * @var \DateTimeImmutable $_dateTime
	 */
	protected $_dateTime;
	
	/**
	 * Constructor
	 *
	 * @param \DateTimeImmutable $dt
	 */
	public function __construct(\DateTimeImmutable $dt) {
		$this->_dateTime = $dt;
	}
	
	/**
	 * Initialize from datetime string.
	 *
	 * @param string $time Time string
	 * @param string|null $tz Timezone, if null use default.
	 * @throws \RuntimeException
	 * @return self
	 */
	public static function fromString($time, $tz = null) {
		try {
			if (!isset($tz)) {
				$tz = date_default_timezone_get();
			}
			return new static(
				new \DateTimeImmutable($time, self::_createTimeZone($tz)));
		} catch (\Exception $e) {
			throw new \RuntimeException(
				"Failed to create DateTime: " .
					 self::_getLastDateTimeImmutableErrorsStr(), 0, $e);
		}
	}
	
	/**
	 * Get time.
	 *
	 * @return \DateTimeImmutable
	 */
	public function dateTime() {
		return $this->_dateTime;
	}
	
	/**
	 * Create DateTimeZone object from string.
	 *
	 * @param string $tz
	 * @throws \UnexpectedValueException
	 * @return \DateTimeZone
	 */
	private static function _createTimeZone($tz) {
		try {
			return new \DateTimeZone($tz);
		} catch (\Exception $e) {
			throw new \UnexpectedValueException("Invalid timezone.", 0, $e);
		}
	}
	
	/**
	 * Get last error caused by DateTimeImmutable.
	 *
	 * @return string
	 */
	private static function _getLastDateTimeImmutableErrorsStr() {
		$errors = \DateTimeImmutable::getLastErrors()["errors"];
		return implode(", ", $errors);
	}
}
