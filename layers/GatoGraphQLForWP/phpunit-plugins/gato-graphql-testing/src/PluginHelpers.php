<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQLTesting;

use PoP\Root\Environment as RootEnvironment;

class PluginHelpers
{
    public static function enablePlugin(): bool
    {
        if (RootEnvironment::isApplicationEnvironmentDev()) {
            return true;
        }

        /**
         * @var string|null
         * phpcs:disable SlevomatCodingStandard.Variables.DisallowSuperGlobalVariable.DisallowedSuperGlobalVariable
         */
        $httpHost = $_SERVER['HTTP_HOST'] ?? null;
        if ($httpHost === null) {
            return false;
        }

        $validTestingDomains = array_merge(
            Environment::getContinuousIntegrationValidTestingDomains(),
            Environment::getLocalDevelopmentValidTestingDomains()
        );

        // Calculate the top level domain (app.site.com => site.com)
        $hostNames = array_reverse(explode('.', $httpHost));
        $domain = $hostNames[1] . '.' . $hostNames[0];
        return in_array($domain, $validTestingDomains);
    }
}
