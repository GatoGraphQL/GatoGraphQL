<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Configuration;

use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\Root\App;
use PoP\Root\Environment as RootEnvironment;

class RequestHelpers
{
    /**
     * If XDebug enabled, append param "XDEBUG_TRIGGER" to debug the request
     */
    public static function maybeAddParamToDebugRequest(string $url): string
    {
        if (RootEnvironment::isApplicationEnvironmentDev() && App::getRequest()->query->has('XDEBUG_TRIGGER')) {
            $url = GeneralUtils::addQueryArgs([
                'XDEBUG_TRIGGER' => '',
            ], $url);
        }
        return $url;
    }
}
