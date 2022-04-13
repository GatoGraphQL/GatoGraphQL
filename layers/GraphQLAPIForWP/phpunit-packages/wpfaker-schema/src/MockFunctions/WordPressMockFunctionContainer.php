<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WPFakerSchema\MockFunctions;

/**
 * Class containing functions copied from WordPress
 * and adapted to mock the desired behavior.
 */
class WordPressMockFunctionContainer
{
    /**
     * Convert given MySQL date string into a different format.
     *
     *  - `$format` should be a PHP date format string.
     *  - 'U' and 'G' formats will return an integer sum of timestamp with timezone offset.
     *  - `$date` is expected to be local time in MySQL format (`Y-m-d H:i:s`).
     *
     * Historically UTC time could be passed to the function to produce Unix timestamp.
     *
     * If `$translate` is true then the given date and format string will
     * be passed to `wp_date()` for translation.
     *
     * @since 0.71
     *
     * @param string $format    Format of the date to return.
     * @param string $date      Date string to convert.
     * @param bool   $translate Whether the return date should be translated. Default true.
     * @return string|int|false Integer if `$format` is 'U' or 'G', string otherwise.
     *                          False on failure.
     */
    public function mySQL2Date(string $format, string $date, bool $translate = true): string|int|false
    {
        if (empty($date)) {
            return false;
        }

        $datetime = date_create($date/*, wp_timezone()*/);

        if (false === $datetime) {
            return false;
        }

        // Returns a sum of timestamp with timezone offset. Ideally should never be used.
        if ('G' === $format || 'U' === $format) {
            return $datetime->getTimestamp() + $datetime->getOffset();
        }

        // if ($translate) {
        //     return wp_date($format, $datetime->getTimestamp());
        // }

        return $datetime->format($format);
    }
}
