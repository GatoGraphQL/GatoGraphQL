<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQLTesting;

class Environment
{
    public final const INTEGRATION_TESTS_SUPPORTED_PLUGIN_NAMESPACES = 'INTEGRATION_TESTS_SUPPORTED_PLUGIN_NAMESPACES';
    public final const CONTINUOUS_INTEGRATION_VALID_TESTING_DOMAINS = 'CONTINUOUS_INTEGRATION_VALID_TESTING_DOMAINS';
    public final const LOCAL_DEVELOPMENT_VALID_TESTING_DOMAINS = 'LOCAL_DEVELOPMENT_VALID_TESTING_DOMAINS';

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
            ? array_map(
                trim(...),
                explode(',', getenv('INTEGRATION_TESTS_SUPPORTED_PLUGIN_NAMESPACES'))
            )
            : ['GatoGraphQL'];
    }

    /**
     * @return string[]
     */
    public static function getContinuousIntegrationValidTestingDomains(): array
    {
        $envVar = self::CONTINUOUS_INTEGRATION_VALID_TESTING_DOMAINS;
        /** @var string|false */
        $envVarValue = getenv($envVar);
        if ($envVarValue === false) {
            return [
                'instawp.xyz',
            ];
        }
        return explode(',', $envVarValue);
    }

    /**
     * @return string[]
     */
    public static function getLocalDevelopmentValidTestingDomains(): array
    {
        $envVar = self::LOCAL_DEVELOPMENT_VALID_TESTING_DOMAINS;
        /** @var string|false */
        $envVarValue = getenv($envVar);
        if ($envVarValue === false) {
            return [
                'lndo.site',
            ];
        }
        return explode(',', $envVarValue);
    }
}
