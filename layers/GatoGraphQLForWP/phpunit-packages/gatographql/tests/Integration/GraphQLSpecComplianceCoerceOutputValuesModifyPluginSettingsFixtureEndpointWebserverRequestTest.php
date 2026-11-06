<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;

class GraphQLSpecComplianceCoerceOutputValuesModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractGraphQLSpecComplianceModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    protected static function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-graphql-spec-compliance-coerce-output-values';
    }

    protected function getSettingsKey(): string
    {
        return SchemaConfigurationFunctionalityModuleResolver::OPTION_COERCE_OUTPUT_VALUES;
    }
}
