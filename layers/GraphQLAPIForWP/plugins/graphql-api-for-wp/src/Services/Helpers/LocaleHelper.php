<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Helpers;

use GraphQLAPI\GraphQLAPI\Static\LocaleUtils;

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
