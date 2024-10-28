<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\SettingsCategoryResolvers;

use GatoGraphQL\GatoGraphQL\Plugin;
use GatoGraphQL\GatoGraphQL\Settings\OptionNamespacerInterface;
use GatoGraphQL\GatoGraphQL\Settings\Options;

class SettingsCategoryResolver extends AbstractSettingsCategoryResolver
{
    public final const ENDPOINT_CONFIGURATION = Plugin::NAMESPACE . '\endpoint-configuration';
    public final const SCHEMA_CONFIGURATION = Plugin::NAMESPACE . '\schema-configuration';
    public final const SCHEMA_TYPE_CONFIGURATION = Plugin::NAMESPACE . '\schema-type-configuration';
    public final const SERVER_CONFIGURATION = Plugin::NAMESPACE . '\server-configuration';
    public final const PLUGIN_CONFIGURATION = Plugin::NAMESPACE . '\plugin-configuration';
    public final const API_KEYS = Plugin::NAMESPACE . '\api-keys';
    public final const PLUGIN_MANAGEMENT = Plugin::NAMESPACE . '\plugin-management';

    private ?OptionNamespacerInterface $optionNamespacer = null;

    final protected function getOptionNamespacer(): OptionNamespacerInterface
    {
        if ($this->optionNamespacer === null) {
            /** @var OptionNamespacerInterface */
            $optionNamespacer = $this->instanceManager->getInstance(OptionNamespacerInterface::class);
            $this->optionNamespacer = $optionNamespacer;
        }
        return $this->optionNamespacer;
    }

    /**
     * @return string[]
     */
    public function getSettingsCategoriesToResolve(): array
    {
        return [
            self::ENDPOINT_CONFIGURATION,
            self::SCHEMA_CONFIGURATION,
            self::SCHEMA_TYPE_CONFIGURATION,
            self::SERVER_CONFIGURATION,
            self::API_KEYS,
            self::PLUGIN_CONFIGURATION,
            self::PLUGIN_MANAGEMENT,
        ];
    }

    public function getName(string $settingsCategory): string
    {
        return match ($settingsCategory) {
            self::ENDPOINT_CONFIGURATION => $this->__('Endpoint Configuration', 'gatographql'),
            self::SCHEMA_CONFIGURATION => $this->__('Schema Configuration', 'gatographql'),
            self::SCHEMA_TYPE_CONFIGURATION => $this->__('Schema Elements Configuration', 'gatographql'),
            self::SERVER_CONFIGURATION => $this->__('Server Configuration', 'gatographql'),
            self::PLUGIN_CONFIGURATION => $this->__('Plugin Configuration', 'gatographql'),
            self::API_KEYS => $this->__('API Keys', 'gatographql'),
            self::PLUGIN_MANAGEMENT => $this->__('Plugin Management', 'gatographql'),
            default => parent::getName($settingsCategory),
        };
    }

    public function getDBOptionName(string $settingsCategory): string
    {
        $option = match ($settingsCategory) {
            self::ENDPOINT_CONFIGURATION => Options::ENDPOINT_CONFIGURATION,
            self::SCHEMA_CONFIGURATION => Options::SCHEMA_CONFIGURATION,
            self::SCHEMA_TYPE_CONFIGURATION => Options::SCHEMA_TYPE_CONFIGURATION,
            self::SERVER_CONFIGURATION => Options::SERVER_CONFIGURATION,
            self::PLUGIN_CONFIGURATION => Options::PLUGIN_CONFIGURATION,
            self::API_KEYS => Options::API_KEYS,
            self::PLUGIN_MANAGEMENT => Options::PLUGIN_MANAGEMENT,
            default => parent::getDBOptionName($settingsCategory),
        };
        return $this->getOptionNamespacer()->namespaceOption($option);
    }

    public function addOptionsFormSubmitButton(string $settingsCategory): bool
    {
        return match ($settingsCategory) {
            self::PLUGIN_MANAGEMENT => false,
            default => parent::addOptionsFormSubmitButton($settingsCategory),
        };
    }
}
