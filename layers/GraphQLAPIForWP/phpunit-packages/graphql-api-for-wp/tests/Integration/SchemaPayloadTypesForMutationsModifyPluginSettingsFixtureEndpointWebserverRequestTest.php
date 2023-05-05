<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;
use PHPUnitForGatoGraphQL\GatoGraphQL\Integration\AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCase;

class SchemaPayloadTypesForMutationsModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    protected function getEndpoint(): string
    {
        return 'graphql/mobile-app/';
    }

    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-schema-payload-types-for-mutations';
    }

    protected function getSettingsKey(): string
    {
        return SchemaConfigurationFunctionalityModuleResolver::USE_PAYLOADABLE_MUTATIONS_DEFAULT_VALUE;
    }

    protected function getModuleID(string $dataName): string
    {
        return 'graphqlapi_graphqlapi_mutations';
    }

    protected function getPluginSettingsNewValue(): mixed
    {
        return false;
    }
}
