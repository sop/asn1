<?php
declare(strict_types = 1);

namespace ASN1\Type\Primitive;

use ASN1\Component\Identifier;
use ASN1\Component\Length;
use ASN1\Exception\DecodeException;
use ASN1\Feature\ElementBase;
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
     * DER restricts format to UTC timezone (Z suffix).
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
     * Constructor.
     *
     * @param \DateTimeImmutable $dt
     */
    public function __construct(\DateTimeImmutable $dt)
    {
        $this->_typeTag = self::TYPE_UTC_TIME;
        parent::__construct($dt);
    }
    
    /**
     *
     * {@inheritdoc}
     */
    protected function _encodedContentDER(): string
    {
        $dt = $this->_dateTime->setTimezone(self::_createTimeZone(self::TZ_UTC));
        return $dt->format("ymdHis\Z");
    }
    
    /**
     *
     * {@inheritdoc}
     * @return self
     */
    protected static function _decodeFromDER(Identifier $identifier,
        string $data, int &$offset): ElementBase
    {
        $idx = $offset;
        $length = Length::expectFromDER($data, $idx)->intLength();
        $str = substr($data, $idx, $length);
        $idx += $length;
        /** @var $match string[] */
        if (!preg_match(self::REGEX, $str, $match)) {
            throw new DecodeException("Invalid UTCTime format.");
        }
        list(, $year, $month, $day, $hour, $minute, $second) = $match;
        $time = $year . $month . $day . $hour . $minute . $second . self::TZ_UTC;
        $dt = \DateTimeImmutable::createFromFormat("!ymdHisT", $time,
            self::_createTimeZone(self::TZ_UTC));
        if (!$dt) {
            throw new DecodeException(
                "Failed to decode UTCTime: " .
                self::_getLastDateTimeImmutableErrorsStr());
        }
        $offset = $idx;
        return new self($dt);
    }
}
