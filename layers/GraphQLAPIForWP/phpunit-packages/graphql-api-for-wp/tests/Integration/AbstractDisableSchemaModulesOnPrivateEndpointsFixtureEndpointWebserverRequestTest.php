<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use PHPUnitForGraphQLAPI\WebserverRequests\AbstractFixtureEnableDisableModuleWordPressAuthenticatedUserWebserverRequestTestCase;

abstract class AbstractDisableSchemaModulesOnPrivateEndpointsFixtureEndpointWebserverRequestTestCase extends AbstractFixtureEnableDisableModuleWordPressAuthenticatedUserWebserverRequestTestCase
{
    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-disable-schema-modules-on-private-endpoints';
    }
}
