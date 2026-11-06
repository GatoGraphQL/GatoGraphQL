<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;

class GraphQLSpecComplianceReturnNoDataOnRequestErrorsModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractGraphQLSpecComplianceModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    protected static function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-graphql-spec-compliance-return-no-data-on-request-errors';
    }

    protected function getSettingsKey(): string
    {
        return SchemaConfigurationFunctionalityModuleResolver::OPTION_RETURN_NO_DATA_ON_REQUEST_ERRORS;
    }
}
