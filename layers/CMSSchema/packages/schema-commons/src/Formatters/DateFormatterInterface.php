<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommons\Formatters;

use DateTimeInterface;

interface DateFormatterInterface
{
    /**
     * Formatted date string or sum of Unix timestamp and timezone offset. False on failure.
     */
    public function format(string $format, DateTimeInterface|string $dateTime): string|int|null;
}
