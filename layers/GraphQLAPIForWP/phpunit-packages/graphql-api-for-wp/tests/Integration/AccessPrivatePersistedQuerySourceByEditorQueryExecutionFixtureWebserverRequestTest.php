<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use PHPUnitForGraphQLAPI\WebserverRequests\Environment;

class AccessPrivatePersistedQuerySourceByEditorQueryExecutionFixtureWebserverRequestTest extends AbstractAccessPrivatePersistedQueryQueryExecutionFixtureWebserverRequestTestCase
{
    use AccessPrivatePersistedQueryFailsQueryExecutionFixtureWebserverRequestTestTrait;

    protected static function getLoginUsername(): string
    {
        return Environment::getIntegrationTestsAuthenticatedEditorUserUsername();
    }

    protected static function getLoginPassword(): string
    {
        return Environment::getIntegrationTestsAuthenticatedEditorUserPassword();
    }

    protected function viewSource(): bool
    {
        return true;
    }
}
