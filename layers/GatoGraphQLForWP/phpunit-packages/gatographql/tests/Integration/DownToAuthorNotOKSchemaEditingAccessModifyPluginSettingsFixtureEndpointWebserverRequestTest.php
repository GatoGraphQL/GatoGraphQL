<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use PHPUnitForGatoGraphQL\WebserverRequests\Environment;

class DownToAuthorNotOKSchemaEditingAccessModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractSchemaEditingAccessModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    use DownToAuthorSchemaEditingAccessModifyPluginSettingsFixtureEndpointWebserverRequestTestTrait;
    use NotOKSchemaEditingAccessModifyPluginSettingsFixtureEndpointWebserverRequestTestTrait;

    protected function getDifferentLoginUsername(): string
    {
        return Environment::getIntegrationTestsAuthenticatedContributorUserUsername();
    }

    protected function getDifferentLoginPassword(): string
    {
        return Environment::getIntegrationTestsAuthenticatedContributorUserPassword();
    }
}
