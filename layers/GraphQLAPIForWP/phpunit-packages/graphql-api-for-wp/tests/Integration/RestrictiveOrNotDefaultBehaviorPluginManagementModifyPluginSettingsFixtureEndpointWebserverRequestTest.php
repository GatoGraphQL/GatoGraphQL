<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use GraphQLAPI\GraphQLAPI\Constants\ResetSettingsOptions;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\PluginManagementFunctionalityModuleResolver;

class RestrictiveOrNotDefaultBehaviorPluginManagementModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCaseCase
{
    protected function getEndpoint(): string
    {
        return 'graphql/mobile-app/';
    }

    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-restrictive-or-not-default-behavior';
    }

    protected function getSettingsKey(): string
    {
        return PluginManagementFunctionalityModuleResolver::OPTION_USE_RESTRICTIVE_OR_NOT_DEFAULT_BEHAVIOR;
    }

    protected function getModuleID(string $dataName): string
    {
        return 'graphqlapi_graphqlapi_reset-settings';
    }

    protected function getPluginSettingsNewValue(): mixed
    {
        return ResetSettingsOptions::RESTRICTIVE;
    }
}
