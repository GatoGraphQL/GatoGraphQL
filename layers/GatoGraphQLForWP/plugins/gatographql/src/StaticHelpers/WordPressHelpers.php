<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\StaticHelpers;

use function wp_doing_cron;

class WordPressHelpers
{
    public static function doingCron(): bool
    {
        return wp_doing_cron();
        // return defined('DOING_CRON') && DOING_CRON;
    }
}
