<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPITesting;

class Environment
{
    public final const INTEGRATION_TESTS_SUPPORTED_PLUGIN_NAMESPACES = 'INTEGRATION_TESTS_SUPPORTED_PLUGIN_NAMESPACES';

    /**
     * Provide the plugin namespaces (eg: also for extensions) for which
     * testing is enabled. Eg: only the corresponding custom post types
     * can be modified, for extra security.
     *
     * @return string[]
     */
    public static function getSupportedPluginNamespaces(): array
    {
        return getenv('INTEGRATION_TESTS_SUPPORTED_PLUGIN_NAMESPACES') !== false
            ? array_map(trim(...), explode(',', getenv('INTEGRATION_TESTS_SUPPORTED_PLUGIN_NAMESPACES')))
            : ['GraphQLAPI\GraphQLAPI'];
    }
}
