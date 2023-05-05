<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use PHPUnitForGatoGraphQL\WebserverRequests\Environment;

class AccessPrivateCustomEndpointByEditorQueryExecutionFixtureWebserverRequestTest extends AbstractAccessPrivateCustomEndpointQueryExecutionFixtureWebserverRequestTestCase
{
    use AccessPrivateCustomEndpointFailsQueryExecutionFixtureWebserverRequestTestTrait;

    protected static function getLoginUsername(): string
    {
        return Environment::getIntegrationTestsAuthenticatedEditorUserUsername();
    }

    protected static function getLoginPassword(): string
    {
        return Environment::getIntegrationTestsAuthenticatedEditorUserPassword();
    }

    protected function accessClient(): bool
    {
        return false;
    }
}
