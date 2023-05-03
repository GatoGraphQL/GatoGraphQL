<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

class UnrestrictedBehaviorFixtureWebserverRequestTest extends AbstractUnrestrictedBehaviorFixtureWebserverRequestTestCaseCase
{
    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-unrestricted-behavior';
    }
}
