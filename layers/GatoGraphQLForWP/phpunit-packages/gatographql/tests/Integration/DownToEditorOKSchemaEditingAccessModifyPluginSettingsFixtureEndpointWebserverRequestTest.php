<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use PHPUnitForGatoGraphQL\WebserverRequests\Environment;

class DownToEditorOKSchemaEditingAccessModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractSchemaEditingAccessModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    use DownToEditorSchemaEditingAccessModifyPluginSettingsFixtureEndpointWebserverRequestTestTrait;
    use OKSchemaEditingAccessModifyPluginSettingsFixtureEndpointWebserverRequestTestTrait;

    protected function getDifferentLoginUsername(): string
    {
        return Environment::getIntegrationTestsAuthenticatedEditorUserUsername();
    }

    protected function getDifferentLoginPassword(): string
    {
        return Environment::getIntegrationTestsAuthenticatedEditorUserPassword();
    }
}
