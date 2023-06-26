<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\SettingsCategoryResolvers;

use GatoGraphQL\GatoGraphQL\Plugin;
use GatoGraphQL\GatoGraphQL\Settings\Options;

class SettingsCategoryResolver extends AbstractSettingsCategoryResolver
{
    public final const SCHEMA_CONFIGURATION = Plugin::NAMESPACE . '\schema-configuration';
    public final const ENDPOINT_CONFIGURATION = Plugin::NAMESPACE . '\endpoint-configuration';
    public final const SERVER_CONFIGURATION = Plugin::NAMESPACE . '\server-configuration';
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
            self::SERVER_CONFIGURATION,
            self::PLUGIN_CONFIGURATION,
            self::LICENSE_KEYS,
            self::PLUGIN_MANAGEMENT,
        ];
    }

    public function getName(string $settingsCategory): string
    {
        return match ($settingsCategory) {
            self::SCHEMA_CONFIGURATION => $this->__('Schema Configuration', 'gato-graphql'),
            self::ENDPOINT_CONFIGURATION => $this->__('Endpoint Configuration', 'gato-graphql'),
            self::SERVER_CONFIGURATION => $this->__('Server Configuration', 'gato-graphql'),
            self::PLUGIN_CONFIGURATION => $this->__('Plugin Configuration', 'gato-graphql'),
            self::LICENSE_KEYS => $this->__('License/API Keys', 'gato-graphql'),
            self::PLUGIN_MANAGEMENT => $this->__('Plugin Management', 'gato-graphql'),
            default => parent::getName($settingsCategory),
        };
    }

    public function getDBOptionName(string $settingsCategory): string
    {
        return match ($settingsCategory) {
            self::SCHEMA_CONFIGURATION => Options::SCHEMA_CONFIGURATION,
            self::ENDPOINT_CONFIGURATION => Options::ENDPOINT_CONFIGURATION,
            self::SERVER_CONFIGURATION => Options::SERVER_CONFIGURATION,
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
