<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

class UnrestrictedBehaviorFixtureWebserverRequestTest extends AbstractUnrestrictedBehaviorFixtureWebserverRequestTestCase
{
    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-unrestricted-behavior';
    }
}
