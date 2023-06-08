<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GatoGraphQL\GatoGraphQL\Constants\ModuleSettingOptions;
use GraphQLByPoP\GraphQLServer\Configuration\MutationSchemes;

class DefaultMutationSchemeModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    protected static function getEndpoint(): string
    {
        // This endpoint has "Support nested mutations?" as "Default"
        return 'graphql/website/';
    }

    protected static function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-default-mutation-scheme';
    }

    protected function getSettingsKey(): string
    {
        return ModuleSettingOptions::DEFAULT_VALUE;
    }

    protected function getModuleID(string $dataName): string
    {
        return 'gatographql_gatographql_nested-mutations';
    }

    protected function getPluginSettingsNewValue(): mixed
    {
        return MutationSchemes::NESTED_WITH_REDUNDANT_ROOT_FIELDS;
    }
}
