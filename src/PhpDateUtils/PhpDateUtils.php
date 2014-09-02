<?php

namespace PhpDateUtils;

class PhpDateUtils {

    public static function newUtcDateTime() {
        return new \DateTime('now', new \DateTimeZone('UTC'));
    }

    public static function newLocalDateTime() {
        return new \DateTime('now', new \DateTimeZone(getenv('LOCAL_TIME_ZONE')));
    }

    public static function localDateTimeToUtcDateTime(\DateTime $localDateTime) {
        $utcDateTime = clone $localDateTime;
        $utcDateTime->setTimeZone(new \DateTimeZone('UTC'));
        return $utcDateTime;
    }

    public static function utcDateTimeToLocalDateTime(\DateTime $utcDateTime) {
        $localDateTime = clone $utcDateTime;
        $localDateTime->setTimeZone(new \DateTimeZone(getenv('LOCAL_TIME_ZONE')));
        return $localDateTime;
    }

    public static function mysqlUtcDateStringToDateTime($dateString) {
        $timezone = new \DateTimeZone('utc');
        return \DateTime::createFromFormat('Y-m-d H:i:s', $dateString, $timezone);
    }

    public static function localDateStringToDateTime($dateString) {
        $timezone = new \DateTimeZone(getenv('LOCAL_TIME_ZONE'));
        $format = getenv('LOCAL_DATE_FORMAT');
        return \DateTime::createFromFormat($format, $dateString, $timezone);
    }

    public static function dateTimeToMysqlDateString(\DateTime $dateTime) {
        return $dateTime->format('Y-m-d H:i:s');
    }

    public static function dateTimeToLocalDateString(\DateTime $dateTime, $options = []) {
        $defaultOptions = [
            'format' => getenv('LOCAL_DATE_FORMAT'),
        ];
        $options = array_merge($defaultOptions, $options);
        return $dateTime->format($options['format']);
    }

    /**
     * Creates a new UTC MySQL formatted date string.
     */
    public static function newUtcMysqlDateString() {
        return self::dateTimeToMysqlDateString(self::newUtcDateTime());
    }

    /**
     * Creates a new date string formatted and timezoned according to env vars
     * LOCAL_DATE_FORMAT and LOCAL_TIME_ZONE
     */
    public static function newLocalDateString() {
        return self::dateTimeToLocalDateString(self::newLocalDateTime());
    }

    /**
     * Takes a UTC MySQL formatted date string and converts it to a locally
     * timezoned, locally formatted date string.
     * Uses LOCAL_DATE_FORMAT and LOCAL_TIME_ZONE env vars to determine locally.
     */
    public static function utcMysqlDateStringToLocalDateString($dateString) {
        $utcDateTime   = self::mysqlUtcDateStringToDateTime($dateString);
        $localDateTime = self::utcDateTimeToLocalDateTime($utcDateTime);
        return self::dateTimeToLocalDateString($localDateTime);
    }

    /**
     * Takes a locally formatted and locally timezoned date string and converts it
     * to a MySQL formatted UTC timezoned date string.
     * Uses LOCAL_DATE_FORMAT and LOCAL_TIME_ZONE env vars to determine locally.
     */
    public static function localDateStringToUtcMysqlDateString($dateString) {
        $localDateTime = self::localDateStringToDateTime($dateString);
        $utcDateTime   = self::localDateTimeToUtcDateTime($localDateTime);
        return self::dateTimeToMysqlDateString($utcDateTime);
    }

}
