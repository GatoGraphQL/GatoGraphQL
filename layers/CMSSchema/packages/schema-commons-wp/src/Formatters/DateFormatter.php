<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommonsWP\Formatters;

use DateTimeInterface;

use PoPCMSSchema\SchemaCommons\Formatters\DateFormatterInterface;
use function mysql2date;

class DateFormatter implements DateFormatterInterface
{
    public function format(string $format, DateTimeInterface|string $dateTime): string|int|null
    {
        if ($dateTime instanceof DateTimeInterface) {
            $dateTime = (string) $dateTime->getTimestamp();
        }
        $date = mysql2date($format, $dateTime);
        return $date === false ? null: $date;
    }
}
