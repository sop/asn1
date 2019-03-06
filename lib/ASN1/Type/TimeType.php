<?php
declare(strict_types = 1);

namespace ASN1\Type;

use ASN1\Element;

/**
 * Base class for all types representing a point in time.
 */
abstract class TimeType extends Element
{
    /**
     * UTC timezone.
     *
     * @var string
     */
    const TZ_UTC = "UTC";
    
    /**
     * Date and time.
     *
     * @var \DateTimeImmutable $_dateTime
     */
    protected $_dateTime;
    
    /**
     * Constructor.
     *
     * @param \DateTimeImmutable $dt
     */
    public function __construct(\DateTimeImmutable $dt)
    {
        $this->_dateTime = $dt;
    }
    
    /**
     * Initialize from datetime string.
     *
     * @link http://php.net/manual/en/datetime.formats.php
     * @param string $time Time string
     * @param string|null $tz Timezone, if null use default.
     * @throws \RuntimeException
     * @return self
     */
    public static function fromString(string $time, string $tz = null): self
    {
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
     * Get the date and time.
     *
     * @return \DateTimeImmutable
     */
    public function dateTime(): \DateTimeImmutable
    {
        return $this->_dateTime;
    }
    
    /**
     * Create DateTimeZone object from string.
     *
     * @param string $tz
     * @throws \UnexpectedValueException If timezone is invalid
     * @return \DateTimeZone
     */
    protected static function _createTimeZone(string $tz): \DateTimeZone
    {
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
    protected static function _getLastDateTimeImmutableErrorsStr(): string
    {
        $errors = \DateTimeImmutable::getLastErrors()["errors"];
        return implode(", ", $errors);
    }
}
