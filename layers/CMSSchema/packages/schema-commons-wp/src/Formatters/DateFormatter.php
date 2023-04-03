<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommonsWP\Formatters;

use DateTime;

use PoPCMSSchema\SchemaCommons\Formatters\DateFormatterInterface;
use function mysql2date;

class DateFormatter implements DateFormatterInterface
{
    public function format(string $format, DateTime|string $dateTime): string|int|false
    {
        return mysql2date($format, $dateTime);
    }
}
