<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommonsWP\Formatters;

use PoPSchema\SchemaCommons\Formatters\DateFormatterInterface;

class DateFormatter implements DateFormatterInterface
{
    public function format(string $format, string $date): string | int | false
    {
        return mysql2date($format, $date);
    }
}
