<?php

namespace PhpDateUtils;

date_default_timezone_set('utc');

class PhpDateUtilsTest extends \PHPUnit_Framework_TestCase {

    const MYSQL_REG_EXP_DATE_STRING = '/^(((\d{4})(-)(0[13578]|10|12)(-)(0[1-9]|[12][0-9]|3[01]))|((\d{4})(-)(0[469]|1‌​1)(-)([0][1-9]|[12][0-9]|30))|((\d{4})(-)(02)(-)(0[1-9]|1[0-9]|2[0-8]))|(([02468]‌​[048]00)(-)(02)(-)(29))|(([13579][26]00)(-)(02)(-)(29))|(([0-9][0-9][0][48])(-)(0‌​2)(-)(29))|(([0-9][0-9][2468][048])(-)(02)(-)(29))|(([0-9][0-9][13579][26])(-)(02‌​)(-)(29)))(\s([0-1][0-9]|2[0-4]):([0-5][0-9]):([0-5][0-9]))$/';

    /**
     * Assert returned string is a valid mysql date string
     */
    function testNewUtcMysqlDateString() {
        $utils = new PhpDateUtils('UTC', 'd m Y H');
        $newDateString = $utils->newUtcMysqlDateString();
        $this->assertNotNull($newDateString);
        $this->assertRegExp(self::MYSQL_REG_EXP_DATE_STRING, $newDateString);
    }

    function testNewLocalDateString() {

        $utils = new PhpDateUtils('UTC', 'd m Y H');
        $newLocalDateString = $utils->newLocalDateString();
        $this->assertRegExp('/^\d+\s\d+\s\d+\s\d+$/', $newLocalDateString);

        //Assert local time zone is different
        $utils = new PhpDateUtils('Pacific/Auckland', 'd m Y H');
        $newNzTimeString = $utils->newLocalDateString();
        $this->assertNotSame($newLocalDateString, $newNzTimeString);
    }

    function testUtcMysqlDateStringToLocalDateString() {
        $utils = new PhpDateUtils('Pacific/Auckland', 'Y m d H i');
        $utcDateString = $utils->newUtcMysqlDateString();

        $localDateString = $utils->utcMysqlDateStringToLocalDateString($utcDateString);

        $this->assertNotSame($utcDateString, $localDateString);

        $options = ['format' => 'Y-m-d H:i:s'];
        $dateString = $utils->utcMysqlDateStringToLocalDateString($utcDateString, $options);
        $this->assertRegExp(self::MYSQL_REG_EXP_DATE_STRING, $dateString);
    }

    function testLocalDateStringToUtcMysqlDateString() {
        $utils = new PhpDateUtils('Pacific/Auckland', 'Y m d H i');
        $localDateString = '2009 04 21 16 40';

        $utcDateString = $utils->localDateStringToUtcMysqlDateString($localDateString);

        $this->assertRegExp(self::MYSQL_REG_EXP_DATE_STRING, $utcDateString);
    }

    function testDateTimeToLocalDateString() {
        $utils = new PhpDateUtils('Pacific/Auckland', 'Y m d H i');

        $dateTime = new \DateTime('2014-01-01 14:12:00');
        $this->assertSame('2014 01 01 14 12', $utils->dateTimeToLocalDateString($dateTime));

        $options = ['format' => 'd / m / Y H / i / s'];
        $this->assertSame('01 / 01 / 2014 14 / 12 / 00', $utils->dateTimeToLocalDateString($dateTime, $options));
    }

    function testUtcMysqlDateStringToLocalDateTime() {
        $utils = new PhpDateUtils('Pacific/Auckland', 'Y m d H i');

        $localDateString = '2014 01 01 14 12';
        $tz = new \DateTimeZone('Pacific/Auckland');
        $expectedLocalDate = \DateTime::createFromFormat('Y m d H i', $localDateString, $tz);

        $utcDateString = $utils->localDateStringToUtcMysqlDateString($localDateString);
        $actualLocalDate = $utils->utcMysqlDateStringToLocalDateTime($utcDateString);

        $this->assertEquals($actualLocalDate->getTimestamp(), $expectedLocalDate->getTimestamp());
    }

}
