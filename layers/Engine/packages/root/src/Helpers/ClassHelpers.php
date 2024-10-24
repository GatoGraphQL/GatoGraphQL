<?php

declare(strict_types=1);

namespace PoP\Root\Helpers;

class ClassHelpers
{
    /**
     * The PSR-4 namespace, with format "Vendor\Project".
     *
     * If own classes have been prefixed, then format will be:
     * "GatoGraphQLScoped\Vendor\Project"
     *
     * @see ci/scoping/plugins/gatographql/scoper-internal.inc.php
     */
    public static function getClassPSR4Namespace(string $class): string
    {
        $parts = explode('\\', $class);
        return $parts[0] . (isset($parts[1]) ? '\\' . $parts[1] : '') . ($parts[0] === 'GatoGraphQLScoped' && isset($parts[2]) ? '\\' . $parts[2] : '');
    }
}
