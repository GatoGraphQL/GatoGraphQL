<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommons\Formatters;

use DateTimeInterface;

class DateFormatter implements DateFormatterInterface
{
    public function format(string $format, DateTimeInterface|string $dateTime): string|int|null
    {
        $time = strtotime($dateTime);
        if ($time === false) {
            return false;
        }
        $date = date($format, $time);
        return $date === false ? null: $date;
    }
}
