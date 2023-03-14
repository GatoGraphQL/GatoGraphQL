<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use GraphQLAPI\GraphQLAPI\Constants\ResetSettingsOptions;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\PluginManagementFunctionalityModuleResolver;

class SafeOrUnsafeDefaultBehaviorAfterResetSettingsModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    protected function getEndpoint(): string
    {
        return 'graphql/';
    }

    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-safe-or-unsafe-default-behavior-after-reset-settings';
    }

    protected function getSettingsKey(): string
    {
        return PluginManagementFunctionalityModuleResolver::OPTION_USE_SAFE_OR_UNSAFE_DEFAULT_BEHAVIOR;
    }

    protected function getModuleID(string $dataName): string
    {
        return 'graphqlapi_graphqlapi_reset-settings';
    }

    protected function getPluginSettingsNewValue(): mixed
    {
        return ResetSettingsOptions::SAFE;
    }
}
