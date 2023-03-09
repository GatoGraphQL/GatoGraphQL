<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\ModuleTypeResolvers;

use GraphQLAPI\GraphQLAPI\Plugin;

/**
 * All module types used in this plugin. Others can be registered by extensions
 */
class ModuleTypeResolver extends AbstractModuleTypeResolver
{
    public final const ACCESS_CONTROL = Plugin::NAMESPACE . '\access-control';
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
     * @return string[]
     */
    public function getModuleTypesToResolve(): array
    {
        return [
            self::ACCESS_CONTROL,
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
        ];
    }

    public function getName(string $moduleType): string
    {
        return match ($moduleType) {
            self::ACCESS_CONTROL => $this->__('Access Control', 'graphql-api'),
            self::CLIENT => $this->__('Client', 'graphql-api'),
            self::ENDPOINT => $this->__('Endpoint', 'graphql-api'),
            self::ENDPOINT_CONFIGURATION => $this->__('Endpoint Configuration', 'graphql-api'),
            self::FUNCTIONALITY => $this->__('Functionality', 'graphql-api'),
            self::OPERATIONAL => $this->__('Operational', 'graphql-api'),
            self::PERFORMANCE => $this->__('Performance', 'graphql-api'),
            self::PLUGIN_GENERAL_SETTINGS => $this->__('General Settings', 'graphql-api'),
            self::PLUGIN_MANAGEMENT => $this->__('Plugin Management', 'graphql-api'),
            self::SCHEMA_CONFIGURATION => $this->__('Schema Configuration', 'graphql-api'),
            self::SCHEMA_TYPE => $this->__('Schema Type', 'graphql-api'),
            self::SCHEMA_DIRECTIVE => $this->__('Schema Directive', 'graphql-api'),
            self::USER_INTERFACE => $this->__('User Interface', 'graphql-api'),
            self::VERSIONING => $this->__('Versioning', 'graphql-api'),
            default => '',
        };
    }
}
