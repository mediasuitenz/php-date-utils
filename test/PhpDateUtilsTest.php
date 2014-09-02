<?php

namespace PhpDateUtils;

class PhpDateUtilsTest extends \PHPUnit_Framework_TestCase {
    
    const MYSQL_REG_EXP_DATE_STRING = '/^(((\d{4})(-)(0[13578]|10|12)(-)(0[1-9]|[12][0-9]|3[01]))|((\d{4})(-)(0[469]|1‌​1)(-)([0][1-9]|[12][0-9]|30))|((\d{4})(-)(02)(-)(0[1-9]|1[0-9]|2[0-8]))|(([02468]‌​[048]00)(-)(02)(-)(29))|(([13579][26]00)(-)(02)(-)(29))|(([0-9][0-9][0][48])(-)(0‌​2)(-)(29))|(([0-9][0-9][2468][048])(-)(02)(-)(29))|(([0-9][0-9][13579][26])(-)(02‌​)(-)(29)))(\s([0-1][0-9]|2[0-4]):([0-5][0-9]):([0-5][0-9]))$/';

    /**
     * Assert returned string is a valid mysql date string
     */
    function testNewUtcMysqlDateString() {
        $newDateString = PhpDateUtils::newUtcMysqlDateString();
        $this->assertNotNull($newDateString);
        $this->assertRegExp(self::MYSQL_REG_EXP_DATE_STRING, $newDateString);
    }

    function testNewLocalDateString() {
        putenv('LOCAL_DATE_FORMAT=d m Y h');
        putenv('LOCAL_TIME_ZONE=UTC');

        $newLocalDateString = PhpDateUtils::newLocalDateString();
        $this->assertRegExp('/^\d+\s\d+\s\d+\s\d+$/', $newLocalDateString);

        //Assert local time zone is different
        putenv('LOCAL_TIME_ZONE=Pacific/Auckland');
        $newNzTimeString = PhpDateUtils::newLocalDateString();
        $this->assertNotSame($newLocalDateString, $newNzTimeString);
    }

    function testUtcMysqlDateStringToLocalDateString() {
        putenv('LOCAL_TIME_ZONE=Pacific/Auckland');
        putenv('LOCAL_DATE_FORMAT=Y m d H i');

        $utcDateString = PhpDateUtils::newUtcMysqlDateString();

        $localDateString = PhpDateUtils::utcMysqlDateStringToLocalDateString($utcDateString);

        $this->assertNotSame($utcDateString, $localDateString);

    }

    function testLocalDateStringToUtcMysqlDateString() {
        putenv('LOCAL_TIME_ZONE=Pacific/Auckland');
        putenv('LOCAL_DATE_FORMAT=Y m d H i');

        $localDateString = '2009 04 21 16 40';

        $utcDateString = PhpDateUtils::localDateStringToUtcMysqlDateString($localDateString);

        $this->assertRegExp(self::MYSQL_REG_EXP_DATE_STRING, $utcDateString);
    }

    function testDateTimeToLocalDateString() {
        putenv('LOCAL_DATE_FORMAT=Y m d H i');
        date_default_timezone_set('Pacific/Auckland');

        $dateTime = new \DateTime('2014-01-01 14:12:00');
        $this->assertSame('2014 01 01 14 12', PhpDateUtils::dateTimeToLocalDateString($dateTime));

        $options = ['format' => 'd / m / Y H / i / s'];
        $this->assertSame('01 / 01 / 2014 14 / 12 / 00', PhpDateUtils::dateTimeToLocalDateString($dateTime, $options));
    }

}
