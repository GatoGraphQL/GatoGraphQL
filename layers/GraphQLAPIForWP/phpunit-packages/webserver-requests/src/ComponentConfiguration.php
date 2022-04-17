<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WebserverRequests;

use PoP\Root\Component\AbstractComponentConfiguration;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    public function getIntegrationTestsWebserverDomain(): ?string
    {
        $envVariable = Environment::INTEGRATION_TESTS_WEBSERVER_DOMAIN;
        $defaultValue = null;

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
        );
    }

    public function getIntegrationTestsAuthenticatedUserUsername(): array
    {
        $envVariable = Environment::INTEGRATION_TESTS_AUTHENTICATED_USER_USERNAME;
        $defaultValue = null;

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
        );
    }

    public function getIntegrationTestsAuthenticatedUserPassword(): array
    {
        $envVariable = Environment::INTEGRATION_TESTS_AUTHENTICATED_USER_PASSWORD;
        $defaultValue = null;

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
        );
    }
}
