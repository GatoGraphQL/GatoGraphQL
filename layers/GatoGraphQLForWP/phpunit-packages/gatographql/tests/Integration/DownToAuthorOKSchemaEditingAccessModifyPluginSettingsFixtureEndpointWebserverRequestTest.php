<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use PHPUnitForGatoGraphQL\WebserverRequests\Environment;

class DownToAuthorOKSchemaEditingAccessModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractSchemaEditingAccessModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    use DownToAuthorSchemaEditingAccessModifyPluginSettingsFixtureEndpointWebserverRequestTestTrait;
    use OKSchemaEditingAccessModifyPluginSettingsFixtureEndpointWebserverRequestTestTrait;

    protected function getDifferentLoginUsername(): string
    {
        return Environment::getIntegrationTestsAuthenticatedAuthorUserUsername();
    }

    protected function getDifferentLoginPassword(): string
    {
        return Environment::getIntegrationTestsAuthenticatedAuthorUserPassword();
    }
}
