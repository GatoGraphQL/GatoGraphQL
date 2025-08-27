<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\StaticHelpers;

class WPCLIHelpers
{
    public static function isWPCLIActive(): bool
    {
        return defined('WP_CLI') && constant('WP_CLI') && class_exists('WP_CLI');
    }
}
