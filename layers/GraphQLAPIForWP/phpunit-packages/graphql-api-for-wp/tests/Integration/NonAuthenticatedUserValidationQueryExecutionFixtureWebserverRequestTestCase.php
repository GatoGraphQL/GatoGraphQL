<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

class NonAuthenticatedUserValidationQueryExecutionFixtureWebserverRequestTestCase extends AbstractNonAuthenticatedUserValidationQueryExecutionFixtureWebserverRequestTestCase
{
    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-non-authenticated-user-validation';
    }
}
