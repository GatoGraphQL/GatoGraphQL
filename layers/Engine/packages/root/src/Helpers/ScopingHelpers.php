<?php

declare(strict_types=1);

namespace PoP\Root\Helpers;

use PoP\Root\Constants\Scoping;

class ScopingHelpers
{
    /**
     * If own classes have been prefixed, then the top-level
     * domain will start with "InternallyPrefixed".
     */
    public static function getPluginScopingTopLevelNamespace(string $pluginName): string
    {
        return Scoping::NAMESPACE_PREFIX . str_replace([' ', '-'], '', $pluginName);
    }
}
