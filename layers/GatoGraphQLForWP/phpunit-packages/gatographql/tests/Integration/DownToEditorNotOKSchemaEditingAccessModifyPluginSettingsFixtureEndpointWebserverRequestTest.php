<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use PHPUnitForGatoGraphQL\WebserverRequests\Environment;

class DownToEditorNotOKSchemaEditingAccessModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractSchemaEditingAccessModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    use DownToEditorSchemaEditingAccessModifyPluginSettingsFixtureEndpointWebserverRequestTestTrait;
    use NotOKSchemaEditingAccessModifyPluginSettingsFixtureEndpointWebserverRequestTestTrait;

    protected function getDifferentLoginUsername(): string
    {
        return Environment::getIntegrationTestsAuthenticatedAuthorUserUsername();
    }

    protected function getDifferentLoginPassword(): string
    {
        return Environment::getIntegrationTestsAuthenticatedAuthorUserPassword();
    }
}
