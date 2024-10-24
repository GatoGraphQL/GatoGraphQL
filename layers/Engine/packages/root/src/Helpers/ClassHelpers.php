<?php

declare(strict_types=1);

namespace PoP\Root\Helpers;

use GatoGraphQL\GatoGraphQL\PluginApp;

class ClassHelpers
{
    /**
     * The PSR-4 namespace, with format "Vendor\Project".
     *
     * If own classes have been prefixed, then the top-level
     * domain will start with "InternallyPrefixed".
     * 
     * For instance, the format for plugin "Gato GraphQL" will then be:
     * "InternallyPrefixedByGatoGraphQL\Vendor\Project"
     *
     * @see ci/scoping/plugins/gatographql/scoper-internal.inc.php
     */
    public static function getClassPSR4Namespace(string $class): string
    {
        $parts = explode('\\', $class);
        return $parts[0] . (isset($parts[1]) ? '\\' . $parts[1] : '') . (str_starts_with($parts[0], 'InternallyPrefixedBy') && isset($parts[2]) ? '\\' . $parts[2] : '');
    }
}
