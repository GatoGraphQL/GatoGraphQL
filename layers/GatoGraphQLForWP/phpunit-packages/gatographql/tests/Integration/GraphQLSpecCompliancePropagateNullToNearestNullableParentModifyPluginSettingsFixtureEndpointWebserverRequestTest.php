<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;

class GraphQLSpecCompliancePropagateNullToNearestNullableParentModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractGraphQLSpecComplianceModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    protected static function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-graphql-spec-compliance-propagate-null-to-nearest-nullable-parent';
    }

    protected function getSettingsKey(): string
    {
        return SchemaConfigurationFunctionalityModuleResolver::OPTION_PROPAGATE_NULL_TO_NEAREST_NULLABLE_PARENT;
    }
}
