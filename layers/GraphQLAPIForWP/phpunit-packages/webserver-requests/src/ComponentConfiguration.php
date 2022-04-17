<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WebserverRequests;

use PoP\Root\Component\AbstractComponentConfiguration;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    public function getIntegrationTestsWebserverDomain(): string
    {
        $envVariable = Environment::INTEGRATION_TESTS_WEBSERVER_DOMAIN;
        $defaultValue = '';

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
        );
    }

    public function getIntegrationTestsAuthenticatedUserUsername(): string
    {
        $envVariable = Environment::INTEGRATION_TESTS_AUTHENTICATED_USER_USERNAME;
        $defaultValue = '';

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
        );
    }

    public function getIntegrationTestsAuthenticatedUserPassword(): string
    {
        $envVariable = Environment::INTEGRATION_TESTS_AUTHENTICATED_USER_PASSWORD;
        $defaultValue = '';

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
        );
    }
}
