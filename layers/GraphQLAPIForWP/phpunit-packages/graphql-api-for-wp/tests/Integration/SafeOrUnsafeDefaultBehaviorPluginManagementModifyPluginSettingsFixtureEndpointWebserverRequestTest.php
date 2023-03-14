<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use GraphQLAPI\GraphQLAPI\Constants\ResetSettingsOptions;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\PluginManagementFunctionalityModuleResolver;

class SafeOrUnsafeDefaultBehaviorPluginManagementModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    protected function getEndpoint(): string
    {
        return 'graphql/';
    }

    /**
     * @param array<string,mixed> $providerItems
     * @return array<string,mixed>
     */
    protected function customizeProviderEndpointEntries(array $providerItems): array
    {
        $providerItems = parent::customizeProviderEndpointEntries($providerItems);
        
        /**
         * Do not use the single endpoint (as it's disabled)
         */
        $providerItems['safe-or-unsafe-default-behavior:1'][2] = 'graphql/mobile-app/';
        
        /**
         * The single endpoint is disabled, then the client returns a 404
         * as an HTML response
         */
        $providerItems['safe-or-unsafe-default-behavior'][0] = 'text/html';
        $providerItems['safe-or-unsafe-default-behavior'][1] = null;

        return $providerItems;
    }

    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-safe-or-unsafe-default-behavior';
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
