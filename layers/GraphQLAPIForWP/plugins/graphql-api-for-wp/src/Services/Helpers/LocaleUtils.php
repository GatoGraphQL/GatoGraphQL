<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Helpers;

class LocaleUtils
{
    /**
     * User's selected language code for the admin panel
     */
    public function getLocaleLanguage(): string
    {
        // locale has shape "en_US". Retrieve the language code only: "en"
        $localeParts = explode('_', \get_locale());
        return $localeParts[0];
    }
}
