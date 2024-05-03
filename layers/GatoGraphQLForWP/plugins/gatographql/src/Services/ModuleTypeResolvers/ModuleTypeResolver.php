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
    public final const INTEGRATIONS = Plugin::NAMESPACE . '\integrations';
    public final const OPERATIONAL = Plugin::NAMESPACE . '\operational';
    public final const PERFORMANCE = Plugin::NAMESPACE . '\performance';
    public final const PLUGIN_GENERAL_SETTINGS = Plugin::NAMESPACE . '\plugin-general-settings';
    public final const PLUGIN_MANAGEMENT = Plugin::NAMESPACE . '\plugin-management';
    public final const SCHEMA_CONFIGURATION = Plugin::NAMESPACE . '\schema-configuration';
    public final const SERVER = Plugin::NAMESPACE . '\server';
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
            self::INTEGRATIONS,
            self::OPERATIONAL,
            self::PERFORMANCE,
            self::PLUGIN_GENERAL_SETTINGS,
            self::PLUGIN_MANAGEMENT,
            self::SCHEMA_CONFIGURATION,
            self::SERVER,
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
            self::CLIENT => $this->__('Client', 'gatographql'),
            self::ENDPOINT => $this->__('Endpoint', 'gatographql'),
            self::ENDPOINT_CONFIGURATION => $this->__('Endpoint Configuration', 'gatographql'),
            self::FUNCTIONALITY => $this->__('Functionality', 'gatographql'),
            self::INTEGRATIONS => $this->__('Integrations', 'gatographql'),
            self::OPERATIONAL => $this->__('Operational', 'gatographql'),
            self::PERFORMANCE => $this->__('Performance', 'gatographql'),
            self::PLUGIN_GENERAL_SETTINGS => $this->__('General Settings', 'gatographql'),
            self::PLUGIN_MANAGEMENT => $this->__('Plugin Management', 'gatographql'),
            self::SCHEMA_CONFIGURATION => $this->__('Schema Configuration', 'gatographql'),
            self::SERVER => $this->__('Server', 'gatographql'),
            self::SCHEMA_TYPE => $this->__('Schema Type', 'gatographql'),
            self::SCHEMA_DIRECTIVE => $this->__('Schema Directive', 'gatographql'),
            self::USER_INTERFACE => $this->__('User Interface', 'gatographql'),
            self::VERSIONING => $this->__('Versioning', 'gatographql'),
            self::EXTENSION => $this->__('Extensions', 'gatographql'),
            self::BUNDLE_EXTENSION => $this->__('Bundle Extensions', 'gatographql'),
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
