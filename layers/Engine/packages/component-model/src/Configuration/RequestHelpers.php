<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Configuration;

use PoP\ComponentModel\Constants\FrameworkParams;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\Root\App;
use PoP\Root\Environment as RootEnvironment;

class RequestHelpers
{
    /**
     * If XDebug enabled, append param "XDEBUG_TRIGGER=debug" to debug the request
     */
    public static function maybeAddParamToDebugRequest(string $url): string
    {
        if (RootEnvironment::isApplicationEnvironmentDev() && App::getRequest()->query->has('XDEBUG_TRIGGER')) {
            $url = GeneralUtils::addQueryArgs([
                FrameworkParams::XDEBUG_TRIGGER => (string)App::getRequest()->query->get('XDEBUG_TRIGGER'),
                /**
                 * Must also pass ?XDEBUG_SESSION_STOP=1 in the URL to avoid
                 * setting cookie XDEBUG_SESSION="1", which launches the
                 * debugger every single time
                 */
                FrameworkParams::XDEBUG_SESSION_STOP => '1',
            ], $url);
        }
        return $url;
    }
}
