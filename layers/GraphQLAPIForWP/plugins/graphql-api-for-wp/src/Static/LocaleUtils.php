<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Static;

/**
 * "Static" because it can be invoked from a TypeModuleResolver's getSettingsDefaultValue
 * and printed on the Settings page, where the services have not yet been initialized
 */
class LocaleUtils
{
    /**
     * User's selected language code for the admin panel
     */
    public static function getLocaleLanguage(): string
    {
        // locale has shape "en_US". Retrieve the language code only: "en"
        $localeParts = explode('_', \get_locale());
        return $localeParts[0];
    }
}
