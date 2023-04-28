<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

trait DisableSchemaModulesOnPrivateEndpointNoChangeAdminEndpointsFixtureEndpointWebserverRequestTestTrait
{
    protected function getResponseFixtureFolder(): string
    {
        return __DIR__ . '/fixture-disable-schema-modules-on-private-endpoints-no-change';
    }

    /**
     * @param array<string,array<string,mixed>> $moduleEntries
     * @return array<string,array<string,mixed>>
     */
    protected function customizeModuleEntries(array $moduleEntries): array
    {
        $moduleEntries['graphqlapi_graphqlapi/schema-users']['response-disabled'] = $moduleEntries['graphqlapi_graphqlapi/schema-users']['response-enabled'];
        return $moduleEntries;
    }
}
