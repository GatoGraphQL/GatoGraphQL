<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GatoGraphQL\GatoGraphQL\Constants\ModuleSettingOptions;
use PHPUnitForGatoGraphQL\WebserverRequests\Environment;
use PoPSchema\SchemaCommons\Constants\Behaviors;

class SettingsModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractChangeLoggedInUserModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    protected static function getEndpoint(): string
    {
        return 'graphql';
    }

    protected static function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-settings';
    }

    protected function getSettingsKey(): string
    {
        return ModuleSettingOptions::BEHAVIOR;
    }

    protected function getModuleID(string $dataName): string
    {
        return 'gatographql_gatographql_schema-settings';
    }

    protected function getPluginSettingsNewValue(): mixed
    {
        return Behaviors::DENY;
    }

    protected function getDifferentLoginUsername(): string
    {
        return Environment::getIntegrationTestsAuthenticatedSubscriberUserUsername();
    }

    protected function getDifferentLoginPassword(): string
    {
        return Environment::getIntegrationTestsAuthenticatedSubscriberUserPassword();
    }
}
