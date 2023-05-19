<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

class SchemaGlobalFieldsWithNestedMutationsFieldsModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractSchemaGlobalFieldsModifyPluginSettingsFixtureEndpointWebserverRequestTest
{
    protected function getEndpoint(): string
    {
        return 'graphql/nested-mutations/';
    }

    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-schema-global-fields-with-nested-mutations';
    }
}
