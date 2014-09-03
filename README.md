php-date-utils
==============

Tools for working with datestrings

require src/PhpDateUtils/PhpDateUtils.php

## usage

```php
$dateUtils = new PhpDateUtils('Your Local Timezone', 'Your Default Date Format');

$dateUtils->newUtcMysqlDateString();
$dateUtils->newLocalDateString();
$dateUtils->utcMysqlDateStringToLocalDateString(:string);
$dateUtils->utcMysqlDateStringToLocalDateTime(:string);
$dateUtils->localDateStringToUtcMysqlDateString(:string);
```
