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
    public final const FUNCTIONALITY = Plugin::NAMESPACE . '\functionality';
    public final const OPERATIONAL = Plugin::NAMESPACE . '\operational';
    public final const PERFORMANCE = Plugin::NAMESPACE . '\performance';
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
            self::FUNCTIONALITY,
            self::OPERATIONAL,
            self::PERFORMANCE,
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
        $names = [
            self::ACCESS_CONTROL => \__('Access Control', 'graphql-api'),
            self::CLIENT => \__('Client', 'graphql-api'),
            self::ENDPOINT => \__('Endpoint', 'graphql-api'),
            self::FUNCTIONALITY => \__('Functionality', 'graphql-api'),
            self::OPERATIONAL => \__('Operational', 'graphql-api'),
            self::PERFORMANCE => \__('Performance', 'graphql-api'),
            self::PLUGIN_MANAGEMENT => \__('Plugin Management', 'graphql-api'),
            self::SCHEMA_CONFIGURATION => \__('Schema Configuration', 'graphql-api'),
            self::SCHEMA_TYPE => \__('Schema Type', 'graphql-api'),
            self::SCHEMA_DIRECTIVE => \__('Schema Directive', 'graphql-api'),
            self::USER_INTERFACE => \__('User Interface', 'graphql-api'),
            self::VERSIONING => \__('Versioning', 'graphql-api'),
        ];
        return $names[$moduleType] ?? '';
    }
}
