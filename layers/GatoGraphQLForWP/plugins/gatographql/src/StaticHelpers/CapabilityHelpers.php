<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\StaticHelpers;

class CapabilityHelpers
{
    public static function getSettingsMenuPageRequiredCapability(): string
    {
        return 'manage_options';
    }
}
