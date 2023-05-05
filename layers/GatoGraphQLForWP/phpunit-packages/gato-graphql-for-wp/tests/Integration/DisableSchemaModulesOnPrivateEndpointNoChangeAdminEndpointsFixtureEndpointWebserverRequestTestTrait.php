<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

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
        $moduleEntries['gatographql_gatographql/schema-users']['response-disabled'] = $moduleEntries['gatographql_gatographql/schema-users']['response-enabled'];
        return $moduleEntries;
    }
}
