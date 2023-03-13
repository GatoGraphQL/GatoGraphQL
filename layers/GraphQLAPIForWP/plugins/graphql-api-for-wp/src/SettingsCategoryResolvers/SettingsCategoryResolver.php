<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\SettingsCategoryResolvers;

use GraphQLAPI\GraphQLAPI\Plugin;
use GraphQLAPI\GraphQLAPI\Settings\Options;

class SettingsCategoryResolver extends AbstractSettingsCategoryResolver
{
    public final const GRAPHQL_API_SETTINGS = Plugin::NAMESPACE . '\graphql-api-settings';
    public final const PLUGIN_SETTINGS = Plugin::NAMESPACE . '\plugin-settings';
    public final const PLUGIN_MANAGEMENT = Plugin::NAMESPACE . '\plugin-management';

    /**
     * @return string[]
     */
    public function getSettingsCategoriesToResolve(): array
    {
        return [
            self::GRAPHQL_API_SETTINGS,
            self::PLUGIN_SETTINGS,
            self::PLUGIN_MANAGEMENT,
        ];
    }

    public function getDescription(string $settingsCategory): ?string
    {
        return match ($settingsCategory) {
            self::GRAPHQL_API_SETTINGS => $this->__('GraphQL API Settings', 'graphql-api'),
            self::PLUGIN_SETTINGS => $this->__('Plugin Settings', 'graphql-api'),
            self::PLUGIN_MANAGEMENT => $this->__('Plugin Management', 'graphql-api'),
            default => parent::getDescription($settingsCategory),
        };
    }

    public function getDBOptionName(string $settingsCategory): string
    {
        return match ($settingsCategory) {
            self::GRAPHQL_API_SETTINGS => Options::GRAPHQL_API_SETTINGS,
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
