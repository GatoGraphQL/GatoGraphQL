<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\PluginSkeleton;

class PluginHelpers
{
    /**
     * The PSR-4 namespace, with format "Vendor\Project"
     */
    public static function getClassPSR4Namespace(string $class): string
    {
        $parts = explode('\\', $class);
        return $parts[0] . (isset($parts[1]) ? '\\' . $parts[1] : '');
    }
}
