<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\SettingsCategoryResolvers;

use GraphQLAPI\GraphQLAPI\Plugin;
use GraphQLAPI\GraphQLAPI\Settings\Options;

class SettingsCategoryResolver extends AbstractSettingsCategoryResolver
{
    public final const SCHEMA_CONFIGURATION = Plugin::NAMESPACE . '\schema-configuration';
    public final const ENDPOINT_CONFIGURATION = Plugin::NAMESPACE . '\endpoint-configuration';
    public final const PLUGIN_CONFIGURATION = Plugin::NAMESPACE . '\plugin-configuration';
    public final const LICENSE_KEYS = Plugin::NAMESPACE . '\license-keys';
    public final const PLUGIN_MANAGEMENT = Plugin::NAMESPACE . '\plugin-management';

    /**
     * @return string[]
     */
    public function getSettingsCategoriesToResolve(): array
    {
        return [
            self::SCHEMA_CONFIGURATION,
            self::ENDPOINT_CONFIGURATION,
            self::PLUGIN_CONFIGURATION,
            self::LICENSE_KEYS,
            self::PLUGIN_MANAGEMENT,
        ];
    }

    public function getName(string $settingsCategory): string
    {
        return match ($settingsCategory) {
            self::SCHEMA_CONFIGURATION => $this->__('Schema Configuration', 'graphql-api'),
            self::ENDPOINT_CONFIGURATION => $this->__('Endpoint Configuration', 'graphql-api'),
            self::PLUGIN_CONFIGURATION => $this->__('Plugin Configuration', 'graphql-api'),
            self::LICENSE_KEYS => $this->__('License/API Keys', 'graphql-api'),
            self::PLUGIN_MANAGEMENT => $this->__('Plugin Management', 'graphql-api'),
            default => parent::getName($settingsCategory),
        };
    }

    public function getDBOptionName(string $settingsCategory): string
    {
        return match ($settingsCategory) {
            self::SCHEMA_CONFIGURATION => Options::SCHEMA_CONFIGURATION,
            self::ENDPOINT_CONFIGURATION => Options::ENDPOINT_CONFIGURATION,
            self::PLUGIN_CONFIGURATION => Options::PLUGIN_CONFIGURATION,
            self::LICENSE_KEYS => Options::LICENSE_KEYS,
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
