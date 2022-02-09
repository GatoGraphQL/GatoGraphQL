<?php

declare(strict_types=1);

namespace PoP\Application;

class Environment
{
    public static function externalSitesRunSameSoftware(): bool
    {
        return getenv('EXTERNAL_SITES_RUN_SAME_SOFTWARE') !== false ? strtolower(getenv('EXTERNAL_SITES_RUN_SAME_SOFTWARE')) === "true" : false;
    }

    public static function disableCustomCMSCode(): bool
    {
        return getenv('DISABLE_CUSTOM_CMS_CODE') !== false ? strtolower(getenv('DISABLE_CUSTOM_CMS_CODE')) === "true" : false;
    }
}
