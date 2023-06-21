<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\ModuleTypeResolvers;

use GatoGraphQL\GatoGraphQL\Plugin;

/**
 * All module types used in this plugin. Others can be registered by extensions
 */
class ModuleTypeResolver extends AbstractModuleTypeResolver
{
    public final const CLIENT = Plugin::NAMESPACE . '\client';
    public final const ENDPOINT = Plugin::NAMESPACE . '\endpoint';
    public final const ENDPOINT_CONFIGURATION = Plugin::NAMESPACE . '\endpoint-configuration';
    public final const FUNCTIONALITY = Plugin::NAMESPACE . '\functionality';
    public final const OPERATIONAL = Plugin::NAMESPACE . '\operational';
    public final const PERFORMANCE = Plugin::NAMESPACE . '\performance';
    public final const PLUGIN_GENERAL_SETTINGS = Plugin::NAMESPACE . '\plugin-general-settings';
    public final const PLUGIN_MANAGEMENT = Plugin::NAMESPACE . '\plugin-management';
    public final const SCHEMA_CONFIGURATION = Plugin::NAMESPACE . '\schema-configuration';
    public final const SCHEMA_TYPE = Plugin::NAMESPACE . '\schema-type';
    public final const SCHEMA_DIRECTIVE = Plugin::NAMESPACE . '\schema-directive';
    public final const USER_INTERFACE = Plugin::NAMESPACE . '\user-interface';
    public final const VERSIONING = Plugin::NAMESPACE . '\versioning';

    /**
     * These are a special type, used to display extensions
     */
    public final const EXTENSION = Plugin::NAMESPACE . '\extension';
    public final const BUNDLE_EXTENSION = Plugin::NAMESPACE . '\bundle-extension';

    /**
     * @return string[]
     */
    public function getModuleTypesToResolve(): array
    {
        return [
            self::CLIENT,
            self::ENDPOINT,
            self::ENDPOINT_CONFIGURATION,
            self::FUNCTIONALITY,
            self::OPERATIONAL,
            self::PERFORMANCE,
            self::PLUGIN_GENERAL_SETTINGS,
            self::PLUGIN_MANAGEMENT,
            self::SCHEMA_CONFIGURATION,
            self::SCHEMA_TYPE,
            self::SCHEMA_DIRECTIVE,
            self::USER_INTERFACE,
            self::VERSIONING,
            self::EXTENSION,
            self::BUNDLE_EXTENSION,
        ];
    }

    public function getName(string $moduleType): string
    {
        return match ($moduleType) {
            self::CLIENT => $this->__('Client', 'gato-graphql'),
            self::ENDPOINT => $this->__('Endpoint', 'gato-graphql'),
            self::ENDPOINT_CONFIGURATION => $this->__('Endpoint Configuration', 'gato-graphql'),
            self::FUNCTIONALITY => $this->__('Functionality', 'gato-graphql'),
            self::OPERATIONAL => $this->__('Operational', 'gato-graphql'),
            self::PERFORMANCE => $this->__('Performance', 'gato-graphql'),
            self::PLUGIN_GENERAL_SETTINGS => $this->__('General Settings', 'gato-graphql'),
            self::PLUGIN_MANAGEMENT => $this->__('Plugin Management', 'gato-graphql'),
            self::SCHEMA_CONFIGURATION => $this->__('Schema Configuration', 'gato-graphql'),
            self::SCHEMA_TYPE => $this->__('Schema Type', 'gato-graphql'),
            self::SCHEMA_DIRECTIVE => $this->__('Schema Directive', 'gato-graphql'),
            self::USER_INTERFACE => $this->__('User Interface', 'gato-graphql'),
            self::VERSIONING => $this->__('Versioning', 'gato-graphql'),
            self::EXTENSION => $this->__('Extensions', 'gato-graphql'),
            self::BUNDLE_EXTENSION => $this->__('Bundle Extensions', 'gato-graphql'),
            default => '',
        };
    }

    public function isHidden(string $moduleType): bool
    {
        return match ($moduleType) {
            self::EXTENSION,
            self::BUNDLE_EXTENSION
                => true,
            default
                => parent::isHidden($moduleType),
        };
    }
}
