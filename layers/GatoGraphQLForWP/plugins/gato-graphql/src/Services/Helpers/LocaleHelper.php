<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Helpers;

use GatoGraphQL\GatoGraphQL\StaticHelpers\LocaleUtils;

class LocaleHelper
{
    /**
     * User's selected language code for the admin panel
     */
    public function getLocaleLanguage(): string
    {
        return LocaleUtils::getLocaleLanguage();
    }
}
