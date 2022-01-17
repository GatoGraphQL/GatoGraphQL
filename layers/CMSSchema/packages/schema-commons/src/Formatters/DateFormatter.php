<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommons\Formatters;

class DateFormatter implements DateFormatterInterface
{
    public function format(string $format, string $date): string | int | false
    {
        return date($format, strtotime($date));
    }
}
