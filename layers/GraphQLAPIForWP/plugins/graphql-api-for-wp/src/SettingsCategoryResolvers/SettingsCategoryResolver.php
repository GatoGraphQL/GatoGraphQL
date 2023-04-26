<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\SettingsCategoryResolvers;

use GraphQLAPI\GraphQLAPI\Plugin;
use GraphQLAPI\GraphQLAPI\Settings\Options;

class SettingsCategoryResolver extends AbstractSettingsCategoryResolver
{
    public final const DEFAULT_SCHEMA_CONFIGURATION = Plugin::NAMESPACE . '\default-schema-configuration';
    public final const ENDPOINT_CONFIGURATION = Plugin::NAMESPACE . '\endpoint-configuration';
    public final const PLUGIN_SETTINGS = Plugin::NAMESPACE . '\plugin-settings';
    public final const PLUGIN_MANAGEMENT = Plugin::NAMESPACE . '\plugin-management';

    /**
     * @return string[]
     */
    public function getSettingsCategoriesToResolve(): array
    {
        return [
            self::DEFAULT_SCHEMA_CONFIGURATION,
            self::ENDPOINT_CONFIGURATION,
            self::PLUGIN_SETTINGS,
            self::PLUGIN_MANAGEMENT,
        ];
    }

    public function getName(string $settingsCategory): string
    {
        return match ($settingsCategory) {
            self::DEFAULT_SCHEMA_CONFIGURATION => $this->__('Default Schema Configuration', 'graphql-api'),
            self::ENDPOINT_CONFIGURATION => $this->__('Endpoint Configuration', 'graphql-api'),
            self::PLUGIN_SETTINGS => $this->__('Plugin Settings', 'graphql-api'),
            self::PLUGIN_MANAGEMENT => $this->__('Plugin Management', 'graphql-api'),
            default => parent::getName($settingsCategory),
        };
    }

    public function getDBOptionName(string $settingsCategory): string
    {
        return match ($settingsCategory) {
            self::DEFAULT_SCHEMA_CONFIGURATION => Options::DEFAULT_SCHEMA_CONFIGURATION,
            self::ENDPOINT_CONFIGURATION => Options::ENDPOINT_CONFIGURATION,
            self::PLUGIN_SETTINGS => Options::PLUGIN_SETTINGS,
            self::PLUGIN_MANAGEMENT => Options::PLUGIN_MANAGEMENT,
            default => parent::getDBOptionName($settingsCategory),
        };
    }

    public function addOptionsFormSubmitButton(string $settingsCategory): bool
    {
        return match ($settingsCategory) {
            self::PLUGIN_MANAGEMENT => false,
            default => parent::addOptionsFormSubmitButton($settingsCategory),
        };
    }
}
