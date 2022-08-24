<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommons\Formatters;

class DateFormatter implements DateFormatterInterface
{
    public function format(string $format, string $date): string|int|false
    {
        $time = strtotime($date);
        if ($time === false) {
            return false;
        }
        return date($format, $time);
    }
}
