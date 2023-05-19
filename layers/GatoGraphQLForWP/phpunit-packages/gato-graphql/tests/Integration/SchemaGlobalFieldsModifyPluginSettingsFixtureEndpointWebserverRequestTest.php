<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

class SchemaGlobalFieldsModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractSchemaGlobalFieldsModifyPluginSettingsFixtureEndpointWebserverRequestTest
{
    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-schema-global-fields';
    }

    protected function getEndpoint(): string
    {
        return 'graphql/mobile-app/';
    }
}
