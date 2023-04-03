<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommons\Formatters;

use DateTime;

class DateFormatter implements DateFormatterInterface
{
    public function format(string $format, DateTime|string $dateTime): string|int|false
    {
        $time = strtotime($dateTime);
        if ($time === false) {
            return false;
        }
        return date($format, $time);
    }
}
