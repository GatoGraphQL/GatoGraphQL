<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;
use GraphQLByPoP\GraphQLServer\Configuration\MutationPayloadTypeOptions;
use PHPUnitForGatoGraphQL\GatoGraphQL\Integration\AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCase;

class SchemaPayloadTypesForMutationsModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    protected static function getEndpoint(): string
    {
        return 'graphql/mobile-app/';
    }

    protected static function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-schema-payload-types-for-mutations';
    }

    protected function getSettingsKey(): string
    {
        return SchemaConfigurationFunctionalityModuleResolver::OPTION_USE_PAYLOADABLE_MUTATIONS_DEFAULT_VALUE;
    }

    protected function getModuleID(string $dataName): string
    {
        return 'gatographql_gatographql_mutations';
    }

    protected function getPluginSettingsNewValue(): mixed
    {
        return MutationPayloadTypeOptions::DO_NOT_USE_PAYLOAD_TYPES_FOR_MUTATIONS;
    }
}
