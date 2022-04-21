<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

class AuthenticatedUserValidationQueryExecutionFixtureWebserverRequestTestCase extends AbstractAuthenticatedUserValidationQueryExecutionFixtureWebserverRequestTestCase
{
    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-authenticated-user-validation';
    }
}
