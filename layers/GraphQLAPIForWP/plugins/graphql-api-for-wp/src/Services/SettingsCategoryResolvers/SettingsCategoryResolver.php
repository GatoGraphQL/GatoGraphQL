<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SettingsCategoryResolvers;

use GraphQLAPI\GraphQLAPI\Plugin;

class SettingsCategoryResolver extends AbstractSettingsCategoryResolver
{
    public final const SETTINGS = Plugin::NAMESPACE . '\settings';
    public final const PLUGIN_SETTINGS = Plugin::NAMESPACE . '\plugin-settings';
    public final const PLUGIN_MANAGEMENT = Plugin::NAMESPACE . '\plugin-management';

    /**
     * @return string[]
     */
    public function getSettingsCategoriesToResolve(): array
    {
        return [
            self::SETTINGS,
            self::PLUGIN_SETTINGS,
            self::PLUGIN_MANAGEMENT,
        ];
    }

    public function getDescription(string $settingsCategory): ?string
    {
        return match ($settingsCategory) {
            self::SETTINGS => \__('Settings for the GraphQL API', 'graphql-api'),
            self::PLUGIN_SETTINGS => \__('Plugin Settings', 'graphql-api'),
            self::PLUGIN_MANAGEMENT => \__('Plugin Management', 'graphql-api'),
            default => parent::getDescription($settingsCategory),
        };
    }
}
