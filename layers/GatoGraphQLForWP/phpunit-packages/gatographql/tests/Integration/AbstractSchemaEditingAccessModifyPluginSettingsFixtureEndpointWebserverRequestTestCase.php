<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GatoGraphQL\GatoGraphQL\Security\UserAuthorizationSchemes\UserAuthorizationSchemeInterface;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\PluginGeneralSettingsFunctionalityModuleResolver;
use PHPUnitForGatoGraphQL\GatoGraphQL\Integration\AbstractChangeLoggedInUserModifyPluginSettingsFixtureEndpointWebserverRequestTestCase;

abstract class AbstractSchemaEditingAccessModifyPluginSettingsFixtureEndpointWebserverRequestTestCase extends AbstractChangeLoggedInUserModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    /**
     * Admin client endpoint
     */
    protected static function getEndpoint(): string
    {
        return 'wp-admin/edit.php?page=gatographql&action=execute_query';
    }

    protected static function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-schema-editing-access';
    }

    protected function getSettingsKey(): string
    {
        return PluginGeneralSettingsFunctionalityModuleResolver::OPTION_EDITING_ACCESS_SCHEME;
    }

    protected function getModuleID(string $dataName): string
    {
        return 'gatographql_gatographql_schema-editing-access';
    }

    protected function getPluginSettingsNewValue(): mixed
    {
        return $this->getUserAuthorizationScheme()->getName();
    }

    abstract protected function getUserAuthorizationScheme(): UserAuthorizationSchemeInterface;

    /**
     * Don't throw an Exception with the expection 500 status code
     */
    protected function getDifferentRequestBasicOptions(): array
    {
        return array_merge(
            parent::getDifferentRequestBasicOptions(),
            [
                'http_errors' => false,
            ]
        );
    }

    /**
     * The original setting (for test ":0") will deny access
     * to the contributor, and throw a 500 exception
     */
    protected function getDifferentExpectedResponseStatusCode(): ?int
    {
        if ($this->is500Exception()) {
            return 500;
        }
        return parent::getDifferentExpectedResponseStatusCode();
    }

    abstract protected function is500Exception(): bool;
}
