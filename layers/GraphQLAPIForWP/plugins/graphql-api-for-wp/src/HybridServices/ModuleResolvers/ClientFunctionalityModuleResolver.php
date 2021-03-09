<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\HybridServices\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\Plugin;
use GraphQLAPI\GraphQLAPI\ModuleSettings\Properties;
use GraphQLAPI\GraphQLAPI\HybridServices\ModuleResolvers\ModuleResolverTrait;
use GraphQLAPI\GraphQLAPI\Services\ModuleTypeResolvers\ModuleTypeResolver;
use GraphQLByPoP\GraphQLClientsForWP\ComponentConfiguration as GraphQLClientsForWPComponentConfiguration;

/**
 * Modules exposing clients to interact with the API
 *
 * @author Leonardo Losoviz <leo@getpop.org>
 */
class ClientFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver
{
    use ModuleResolverTrait;

    public const GRAPHIQL_FOR_SINGLE_ENDPOINT = Plugin::NAMESPACE . '\graphiql-for-single-endpoint';
    public const GRAPHIQL_FOR_CUSTOM_ENDPOINTS = Plugin::NAMESPACE . '\graphiql-for-custom-endpoints';
    public const INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT = Plugin::NAMESPACE . '\interactive-schema-for-single-endpoint';
    public const INTERACTIVE_SCHEMA_FOR_CUSTOM_ENDPOINTS = Plugin::NAMESPACE . '\interactive-schema-for-custom-endpoints';
    public const GRAPHIQL_EXPLORER = Plugin::NAMESPACE . '\graphiql-explorer';

    /**
     * Setting options
     */
    public const OPTION_USE_IN_ADMIN_CLIENT = 'use-in-admin-client';
    public const OPTION_USE_IN_ADMIN_PERSISTED_QUERIES = 'use-in-admin-persisted-queries';
    public const OPTION_USE_IN_PUBLIC_CLIENT_FOR_SINGLE_ENDPOINT = 'use-in-public-client-for-single-endpoint';
    public const OPTION_USE_IN_PUBLIC_CLIENT_FOR_CUSTOM_ENDPOINTS = 'use-in-public-client-for-custom-endpoints';

    /**
     * @return string[]
     */
    public function getModulesToResolve(): array
    {
        return [
            self::GRAPHIQL_FOR_SINGLE_ENDPOINT,
            self::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT,
            self::GRAPHIQL_FOR_CUSTOM_ENDPOINTS,
            self::INTERACTIVE_SCHEMA_FOR_CUSTOM_ENDPOINTS,
            self::GRAPHIQL_EXPLORER,
        ];
    }

    /**
     * Enable to customize a specific UI for the module
     */
    public function getModuleType(string $module): string
    {
        return ModuleTypeResolver::CLIENT;
    }

    /**
     * @return array<array> List of entries that must be satisfied, each entry is an array where at least 1 module must be satisfied
     */
    public function getDependedModuleLists(string $module): array
    {
        switch ($module) {
            case self::GRAPHIQL_FOR_SINGLE_ENDPOINT:
            case self::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT:
                return [
                    [
                        EndpointFunctionalityModuleResolver::SINGLE_ENDPOINT,
                    ],
                ];
            case self::GRAPHIQL_FOR_CUSTOM_ENDPOINTS:
            case self::INTERACTIVE_SCHEMA_FOR_CUSTOM_ENDPOINTS:
                return [
                    [
                        EndpointFunctionalityModuleResolver::CUSTOM_ENDPOINTS,
                    ],
                ];
            case self::GRAPHIQL_EXPLORER:
                return [];
        }
        return parent::getDependedModuleLists($module);
    }

    public function areRequirementsSatisfied(string $module): bool
    {
        switch ($module) {
            case self::GRAPHIQL_FOR_SINGLE_ENDPOINT:
            case self::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT:
                /**
                 * Permalink structure must be enabled
                 */
                return !empty(\get_option('permalink_structure'));
        }
        return parent::areRequirementsSatisfied($module);
    }

    public function getName(string $module): string
    {
        $names = [
            self::GRAPHIQL_FOR_SINGLE_ENDPOINT => \__('GraphiQL for Single Endpoint', 'graphql-api'),
            self::GRAPHIQL_FOR_CUSTOM_ENDPOINTS => \__('GraphiQL for Custom Endpoints', 'graphql-api'),
            self::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT => \__('Interactive Schema for Single Endpoint', 'graphql-api'),
            self::INTERACTIVE_SCHEMA_FOR_CUSTOM_ENDPOINTS => \__('Interactive Schema for Custom Endpoints', 'graphql-api'),
            self::GRAPHIQL_EXPLORER => \__('GraphiQL Explorer', 'graphql-api'),
        ];
        return $names[$module] ?? $module;
    }

    public function getDescription(string $module): string
    {
        switch ($module) {
            case self::GRAPHIQL_FOR_SINGLE_ENDPOINT:
                return \sprintf(
                    \__('Make a public GraphiQL client available under <code>%s</code>, to execute queries against the single endpoint. It requires pretty permalinks enabled', 'graphql-api'),
                    GraphQLClientsForWPComponentConfiguration::getGraphiQLClientEndpoint()
                );
            case self::GRAPHIQL_FOR_CUSTOM_ENDPOINTS:
                return \__('Enable custom endpoints to be attached their own GraphiQL client, to execute queries against them', 'graphql-api');
            case self::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT:
                return \sprintf(
                    \__('Make a public Interactive Schema client available under <code>%s</code>, to visualize the schema accessible through the single endpoint. It requires pretty permalinks enabled', 'graphql-api'),
                    GraphQLClientsForWPComponentConfiguration::getVoyagerClientEndpoint()
                );
            case self::INTERACTIVE_SCHEMA_FOR_CUSTOM_ENDPOINTS:
                return \__('Enable custom endpoints to be attached their own Interactive schema client, to visualize the custom schema subset', 'graphql-api');
            case self::GRAPHIQL_EXPLORER:
                return \__('Add the Explorer widget to the GraphiQL client, to simplify coding the query (by point-and-clicking on the fields)', 'graphql-api');
        }
        return parent::getDescription($module);
    }

    /**
     * Default value for an option set by the module
     *
     * @param string $module
     * @param string $option
     * @return mixed Anything the setting might be: an array|string|bool|int|null
     */
    public function getSettingsDefaultValue(string $module, string $option)
    {
        $defaultValues = [
            self::GRAPHIQL_FOR_SINGLE_ENDPOINT => [
                EndpointFunctionalityModuleResolver::OPTION_PATH => '/graphiql/',
            ],
            self::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT => [
                EndpointFunctionalityModuleResolver::OPTION_PATH => '/schema/',
            ],
            self::GRAPHIQL_EXPLORER => [
                self::OPTION_USE_IN_ADMIN_CLIENT => true,
                self::OPTION_USE_IN_ADMIN_PERSISTED_QUERIES => true,
                self::OPTION_USE_IN_PUBLIC_CLIENT_FOR_SINGLE_ENDPOINT => true,
                self::OPTION_USE_IN_PUBLIC_CLIENT_FOR_CUSTOM_ENDPOINTS => true,
            ],
        ];
        return $defaultValues[$module][$option];
    }

    /**
     * Array with the inputs to show as settings for the module
     *
    * @return array<array> List of settings for the module, each entry is an array with property => value
     */
    public function getSettings(string $module): array
    {
        $moduleSettings = parent::getSettings($module);
        // Do the if one by one, so that the SELECT do not get evaluated unless needed
        if ($module == self::GRAPHIQL_FOR_SINGLE_ENDPOINT) {
            $option = EndpointFunctionalityModuleResolver::OPTION_PATH;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Client path', 'graphql-api'),
                Properties::DESCRIPTION => \__('URL path to access the public GraphiQL client', 'graphql-api'),
                Properties::TYPE => Properties::TYPE_STRING,
            ];
        } elseif ($module == self::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT) {
            $option = EndpointFunctionalityModuleResolver::OPTION_PATH;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Client path', 'graphql-api'),
                Properties::DESCRIPTION => \__('URL path to access the public Interactive Schema client', 'graphql-api'),
                Properties::TYPE => Properties::TYPE_STRING,
            ];
        } elseif ($module == self::GRAPHIQL_EXPLORER) {
            $option = self::OPTION_USE_IN_ADMIN_CLIENT;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Admin client', 'graphql-api'),
                Properties::DESCRIPTION => \__('Use the Explorer in the GraphiQL client in the admin area?', 'graphql-api'),
                Properties::TYPE => Properties::TYPE_BOOL,
            ];
            if ($this->moduleRegistry->isModuleEnabled(EndpointFunctionalityModuleResolver::PERSISTED_QUERIES)) {
                $option = self::OPTION_USE_IN_ADMIN_PERSISTED_QUERIES;
                $moduleSettings[] = [
                    Properties::INPUT => $option,
                    Properties::NAME => $this->getSettingOptionName(
                        $module,
                        $option
                    ),
                    Properties::TITLE => \__('Persisted queries', 'graphql-api'),
                    Properties::DESCRIPTION => \__('Use the Explorer when creating persisted queries in the admin area?', 'graphql-api'),
                    Properties::TYPE => Properties::TYPE_BOOL,
                ];
            }
            if ($this->moduleRegistry->isModuleEnabled(self::GRAPHIQL_FOR_SINGLE_ENDPOINT)) {
                $option = self::OPTION_USE_IN_PUBLIC_CLIENT_FOR_SINGLE_ENDPOINT;
                $moduleSettings[] = [
                    Properties::INPUT => $option,
                    Properties::NAME => $this->getSettingOptionName(
                        $module,
                        $option
                    ),
                    Properties::TITLE => \__('Single endpoint public client', 'graphql-api'),
                    Properties::DESCRIPTION => \__('Use the Explorer in the single endpoint\'s public GraphiQL client?', 'graphql-api'),
                    Properties::TYPE => Properties::TYPE_BOOL,
                ];
            }
            if ($this->moduleRegistry->isModuleEnabled(self::GRAPHIQL_FOR_CUSTOM_ENDPOINTS)) {
                $option = self::OPTION_USE_IN_PUBLIC_CLIENT_FOR_CUSTOM_ENDPOINTS;
                $moduleSettings[] = [
                    Properties::INPUT => $option,
                    Properties::NAME => $this->getSettingOptionName(
                        $module,
                        $option
                    ),
                    Properties::TITLE => \__('Custom endpoint public clients', 'graphql-api'),
                    Properties::DESCRIPTION => \__('Use the Explorer in the custom endpoints\' public GraphiQL client?', 'graphql-api'),
                    Properties::TYPE => Properties::TYPE_BOOL,
                ];
            }
        }
        return $moduleSettings;
    }
}
