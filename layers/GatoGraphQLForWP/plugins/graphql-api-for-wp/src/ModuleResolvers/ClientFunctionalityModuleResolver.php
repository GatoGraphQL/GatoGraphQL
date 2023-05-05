<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\Constants\ModuleSettingOptions;
use GatoGraphQL\GatoGraphQL\ContentProcessors\MarkdownContentParserInterface;
use GatoGraphQL\GatoGraphQL\ModuleSettings\Properties;
use GatoGraphQL\GatoGraphQL\Plugin;
use GatoGraphQL\GatoGraphQL\SettingsCategoryResolvers\SettingsCategoryResolver;
use GraphQLByPoP\GraphQLClientsForWP\Module as GraphQLClientsForWPModule;
use GraphQLByPoP\GraphQLClientsForWP\ModuleConfiguration as GraphQLClientsForWPModuleConfiguration;
use PoP\Root\App;

/**
 * Modules exposing clients to interact with the API
 *
 * @author Leonardo Losoviz <leo@getpop.org>
 */
class ClientFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver
{
    use ModuleResolverTrait;
    use ClientFunctionalityModuleResolverTrait;

    public final const GRAPHIQL_FOR_SINGLE_ENDPOINT = Plugin::NAMESPACE . '\graphiql-for-single-endpoint';
    public final const GRAPHIQL_FOR_CUSTOM_ENDPOINTS = Plugin::NAMESPACE . '\graphiql-for-custom-endpoints';
    public final const INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT = Plugin::NAMESPACE . '\interactive-schema-for-single-endpoint';
    public final const INTERACTIVE_SCHEMA_FOR_CUSTOM_ENDPOINTS = Plugin::NAMESPACE . '\interactive-schema-for-custom-endpoints';

    private ?MarkdownContentParserInterface $markdownContentParser = null;

    final public function setMarkdownContentParser(MarkdownContentParserInterface $markdownContentParser): void
    {
        $this->markdownContentParser = $markdownContentParser;
    }
    final protected function getMarkdownContentParser(): MarkdownContentParserInterface
    {
        /** @var MarkdownContentParserInterface */
        return $this->markdownContentParser ??= $this->instanceManager->getInstance(MarkdownContentParserInterface::class);
    }

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
        ];
    }

    public function getSettingsCategory(string $module): string
    {
        return SettingsCategoryResolver::ENDPOINT_CONFIGURATION;
    }

    /**
     * @return array<string[]> List of entries that must be satisfied, each entry is an array where at least 1 module must be satisfied
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
        return match ($module) {
            self::GRAPHIQL_FOR_SINGLE_ENDPOINT => \__('GraphiQL for Single Endpoint', 'gato-graphql'),
            self::GRAPHIQL_FOR_CUSTOM_ENDPOINTS => \__('GraphiQL for Custom Endpoints', 'gato-graphql'),
            self::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT => \__('Interactive Schema for Single Endpoint', 'gato-graphql'),
            self::INTERACTIVE_SCHEMA_FOR_CUSTOM_ENDPOINTS => \__('Interactive Schema for Custom Endpoints', 'gato-graphql'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        /** @var GraphQLClientsForWPModuleConfiguration */
        $moduleConfiguration = App::getModule(GraphQLClientsForWPModule::class)->getConfiguration();
        switch ($module) {
            case self::GRAPHIQL_FOR_SINGLE_ENDPOINT:
                return \sprintf(
                    \__('Make a public GraphiQL client available under <code>%s</code>, to execute queries against the single endpoint. It requires pretty permalinks enabled', 'gato-graphql'),
                    $moduleConfiguration->getGraphiQLClientEndpoint()
                );
            case self::GRAPHIQL_FOR_CUSTOM_ENDPOINTS:
                return \__('Enable custom endpoints to be attached their own GraphiQL client, to execute queries against them', 'gato-graphql');
            case self::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT:
                return \sprintf(
                    \__('Make a public Interactive Schema client available under <code>%s</code>, to visualize the schema accessible through the single endpoint. It requires pretty permalinks enabled', 'gato-graphql'),
                    $moduleConfiguration->getVoyagerClientEndpoint()
                );
            case self::INTERACTIVE_SCHEMA_FOR_CUSTOM_ENDPOINTS:
                return \__('Enable custom endpoints to be attached their own Interactive schema client, to visualize the custom schema subset', 'gato-graphql');
        }
        return parent::getDescription($module);
    }

    /**
     * Default value for an option set by the module
     */
    public function getSettingsDefaultValue(string $module, string $option): mixed
    {
        $defaultValues = [
            self::GRAPHIQL_FOR_SINGLE_ENDPOINT => [
                ModuleSettingOptions::PATH => 'graphiql/',
            ],
            self::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT => [
                ModuleSettingOptions::PATH => 'schema/',
            ],
        ];
        return $defaultValues[$module][$option] ?? null;
    }

    /**
     * Array with the inputs to show as settings for the module
     *
    * @return array<array<string,mixed>> List of settings for the module, each entry is an array with property => value
     */
    public function getSettings(string $module): array
    {
        $moduleSettings = parent::getSettings($module);
        // Do the if one by one, so that the SELECT do not get evaluated unless needed
        if ($module === self::GRAPHIQL_FOR_SINGLE_ENDPOINT) {
            $option = ModuleSettingOptions::PATH;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Client path', 'gato-graphql'),
                Properties::DESCRIPTION => \__('URL path to access the public GraphiQL client', 'gato-graphql'),
                Properties::TYPE => Properties::TYPE_STRING,
            ];
        } elseif ($module === self::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT) {
            $option = ModuleSettingOptions::PATH;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Client path', 'gato-graphql'),
                Properties::DESCRIPTION => \__('URL path to access the public Interactive Schema client', 'gato-graphql'),
                Properties::TYPE => Properties::TYPE_STRING,
            ];
        }
        return $moduleSettings;
    }
}
