<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;

class GraphQLSpecComplianceAddResponsePathToErrorsModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractGraphQLSpecComplianceModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    protected static function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-graphql-spec-compliance-add-response-path-to-errors';
    }

    protected function getSettingsKey(): string
    {
        return SchemaConfigurationFunctionalityModuleResolver::OPTION_ADD_RESPONSE_PATH_TO_ERRORS;
    }
}
