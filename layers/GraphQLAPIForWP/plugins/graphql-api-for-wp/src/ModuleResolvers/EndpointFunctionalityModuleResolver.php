<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

use PoP\Root\App;
use GraphQLAPI\GraphQLAPI\Constants\ModuleSettingOptions;
use GraphQLAPI\GraphQLAPI\ContentProcessors\MarkdownContentParserInterface;
use GraphQLAPI\GraphQLAPI\ModuleSettings\Properties;
use GraphQLAPI\GraphQLAPI\Plugin;
use GraphQLAPI\GraphQLAPI\PluginEnvironment;
use GraphQLByPoP\GraphQLEndpointForWP\Component as GraphQLEndpointForWPComponent;
use GraphQLByPoP\GraphQLEndpointForWP\ComponentConfiguration as GraphQLEndpointForWPComponentConfiguration;

class EndpointFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver
{
    use ModuleResolverTrait;
    use EndpointFunctionalityModuleResolverTrait;

    public final const SINGLE_ENDPOINT = Plugin::NAMESPACE . '\single-endpoint';
    public final const PERSISTED_QUERIES = Plugin::NAMESPACE . '\persisted-queries';
    public final const CUSTOM_ENDPOINTS = Plugin::NAMESPACE . '\custom-endpoints';
    public final const API_HIERARCHY = Plugin::NAMESPACE . '\api-hierarchy';

    private ?MarkdownContentParserInterface $markdownContentParser = null;

    final public function setMarkdownContentParser(MarkdownContentParserInterface $markdownContentParser): void
    {
        $this->markdownContentParser = $markdownContentParser;
    }
    final protected function getMarkdownContentParser(): MarkdownContentParserInterface
    {
        return $this->markdownContentParser ??= $this->instanceManager->getInstance(MarkdownContentParserInterface::class);
    }

    /**
     * @return string[]
     */
    public function getModulesToResolve(): array
    {
        return [
            self::SINGLE_ENDPOINT,
            self::CUSTOM_ENDPOINTS,
            self::PERSISTED_QUERIES,
            self::API_HIERARCHY,
        ];
    }

    /**
     * @return array<array> List of entries that must be satisfied, each entry is an array where at least 1 module must be satisfied
     */
    public function getDependedModuleLists(string $module): array
    {
        switch ($module) {
            case self::SINGLE_ENDPOINT:
                return [];
            case self::PERSISTED_QUERIES:
            case self::CUSTOM_ENDPOINTS:
                return [
                    [
                        SchemaConfigurationFunctionalityModuleResolver::SCHEMA_CONFIGURATION,
                    ],
                ];
            case self::API_HIERARCHY:
                return [
                    [
                        self::PERSISTED_QUERIES,
                        self::CUSTOM_ENDPOINTS,
                    ],
                ];
        }
        return parent::getDependedModuleLists($module);
    }

    public function getName(string $module): string
    {
        $names = [
            self::SINGLE_ENDPOINT => \__('Single Endpoint', 'graphql-api'),
            self::PERSISTED_QUERIES => \__('Persisted Queries', 'graphql-api'),
            self::CUSTOM_ENDPOINTS => \__('Custom Endpoints', 'graphql-api'),
            self::API_HIERARCHY => \__('API Hierarchy', 'graphql-api'),
        ];
        return $names[$module] ?? $module;
    }

    public function getDescription(string $module): string
    {
        /** @var GraphQLEndpointForWPComponentConfiguration */
        $componentConfiguration = App::getComponent(GraphQLEndpointForWPComponent::class)->getConfiguration();
        switch ($module) {
            case self::SINGLE_ENDPOINT:
                return \sprintf(
                    \__('Expose a single GraphQL endpoint under <code>%s</code>, with unrestricted access', 'graphql-api'),
                    $componentConfiguration->getGraphQLAPIEndpoint()
                );
            case self::PERSISTED_QUERIES:
                return \__('Expose predefined responses through a custom URL, akin to using GraphQL queries to publish REST endpoints', 'graphql-api');
            case self::CUSTOM_ENDPOINTS:
                return \__('Expose different subsets of the schema for different targets, such as users (clients, employees, etc), applications (website, mobile app, etc), context (weekday, weekend, etc), and others', 'graphql-api');
            case self::API_HIERARCHY:
                return \__('Create a hierarchy of API endpoints extending from other endpoints, and inheriting their properties', 'graphql-api');
        }
        return parent::getDescription($module);
    }

    public function isEnabledByDefault(string $module): bool
    {
        switch ($module) {
            case self::SINGLE_ENDPOINT:
                return PluginEnvironment::areUnsafeDefaultsEnabled();
        }
        return parent::isEnabledByDefault($module);
    }

    /**
     * Default value for an option set by the module
     */
    public function getSettingsDefaultValue(string $module, string $option): mixed
    {
        $defaultValues = [
            self::SINGLE_ENDPOINT => [
                ModuleSettingOptions::PATH => '/graphql/',
            ],
            self::CUSTOM_ENDPOINTS => [
                ModuleSettingOptions::PATH => 'graphql',
            ],
            self::PERSISTED_QUERIES => [
                ModuleSettingOptions::PATH => 'graphql-query',
            ],
        ];
        return $defaultValues[$module][$option] ?? null;
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
        if ($module == self::SINGLE_ENDPOINT) {
            $option = ModuleSettingOptions::PATH;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Endpoint path', 'graphql-api'),
                Properties::DESCRIPTION => \__('URL path to expose the single GraphQL endpoint', 'graphql-api'),
                Properties::TYPE => Properties::TYPE_STRING,
            ];
        } elseif ($module == self::CUSTOM_ENDPOINTS) {
            $option = ModuleSettingOptions::PATH;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Base path', 'graphql-api'),
                Properties::DESCRIPTION => \__('URL base path to expose the Custom Endpoint', 'graphql-api'),
                Properties::TYPE => Properties::TYPE_STRING,
            ];
        } elseif ($module == self::PERSISTED_QUERIES) {
            $option = ModuleSettingOptions::PATH;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Base path', 'graphql-api'),
                Properties::DESCRIPTION => \__('URL base path to expose the Persisted Query', 'graphql-api'),
                Properties::TYPE => Properties::TYPE_STRING,
            ];
        }
        return $moduleSettings;
    }
}
