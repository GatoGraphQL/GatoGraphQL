<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GatoGraphQL\GatoGraphQL\Constants\ResetSettingsOptions;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\PluginManagementFunctionalityModuleResolver;

class RestrictiveOrNotDefaultBehaviorPluginManagementModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    protected static function getEndpoint(): string
    {
        return 'graphql';
    }

    protected static function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-restrictive-or-not-default-behavior';
    }

    protected function getSettingsKey(): string
    {
        return PluginManagementFunctionalityModuleResolver::OPTION_USE_RESTRICTIVE_OR_NOT_DEFAULT_BEHAVIOR;
    }

    protected function getModuleID(string $dataName): string
    {
        return 'gatographql_gatographql_reset-settings';
    }

    protected function getPluginSettingsNewValue(): mixed
    {
        return ResetSettingsOptions::RESTRICTIVE;
    }
}
