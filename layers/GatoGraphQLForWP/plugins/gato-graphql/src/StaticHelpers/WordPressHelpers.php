<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\StaticHelpers;

class WordPressHelpers
{
    public static function doingCron(): bool
    {
        return defined('DOING_CRON') && DOING_CRON;
    }
}
