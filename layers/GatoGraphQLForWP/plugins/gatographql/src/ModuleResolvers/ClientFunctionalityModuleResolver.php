<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\Constants\HTMLCodes;
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
    public final const INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT = Plugin::NAMESPACE . '\interactive-schema-for-single-endpoint';

    private ?MarkdownContentParserInterface $markdownContentParser = null;

    final public function setMarkdownContentParser(MarkdownContentParserInterface $markdownContentParser): void
    {
        $this->markdownContentParser = $markdownContentParser;
    }
    final protected function getMarkdownContentParser(): MarkdownContentParserInterface
    {
        if ($this->markdownContentParser === null) {
            /** @var MarkdownContentParserInterface */
            $markdownContentParser = $this->instanceManager->getInstance(MarkdownContentParserInterface::class);
            $this->markdownContentParser = $markdownContentParser;
        }
        return $this->markdownContentParser;
    }

    /**
     * @return string[]
     */
    public function getModulesToResolve(): array
    {
        return [
            self::GRAPHIQL_FOR_SINGLE_ENDPOINT,
            self::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT,
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
            self::GRAPHIQL_FOR_SINGLE_ENDPOINT => \__('GraphiQL for Single Endpoint', 'gatographql'),
            self::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT => \__('Interactive Schema for Single Endpoint', 'gatographql'),
            default => $module,
        };
    }

    public function isEnabledByDefault(string $module): bool
    {
        return match ($module) {
            self::GRAPHIQL_FOR_SINGLE_ENDPOINT,
            self::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT
                => false,
            default => parent::isEnabledByDefault($module),
        };
    }

    public function getDescription(string $module): string
    {
        /** @var GraphQLClientsForWPModuleConfiguration */
        $moduleConfiguration = App::getModule(GraphQLClientsForWPModule::class)->getConfiguration();
        switch ($module) {
            case self::GRAPHIQL_FOR_SINGLE_ENDPOINT:
                return \sprintf(
                    \__('Make a public GraphiQL client available under <code>%s</code>, to execute queries against the single endpoint. It requires pretty permalinks enabled', 'gatographql'),
                    $moduleConfiguration->getGraphiQLClientEndpoint()
                );
            case self::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT:
                return \sprintf(
                    \__('Make a public Interactive Schema client available under <code>%s</code>, to visualize the schema accessible through the single endpoint. It requires pretty permalinks enabled', 'gatographql'),
                    $moduleConfiguration->getVoyagerClientEndpoint()
                );
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
        $moreInfoLabelPlaceholder = '<a href="%1$s" target="_blank">%2$s' . HTMLCodes::OPEN_IN_NEW_WINDOW . '</a>';
        // Do the if one by one, so that the SELECT do not get evaluated unless needed
        if ($module === self::GRAPHIQL_FOR_SINGLE_ENDPOINT) {
            $option = ModuleSettingOptions::PATH;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Client path', 'gatographql'),
                Properties::DESCRIPTION => sprintf(
                    \__('URL path to access the public GraphiQL client<br/><br/><strong>More info: %s</strong>', 'gatographql'),
                    sprintf(
                        $moreInfoLabelPlaceholder,
                        'https://gatographql.com/guides/config/configuring-the-graphiql-client-for-the-single-endpoint',
                        \__('Configuring the GraphiQL client for the single endpoint', 'gatographql')
                    )
                ),
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
                Properties::TITLE => \__('Client path', 'gatographql'),
                Properties::DESCRIPTION => sprintf(
                    \__('URL path to access the public Interactive Schema client<br/><br/><strong>More info: %s</strong>', 'gatographql'),
                    sprintf(
                        $moreInfoLabelPlaceholder,
                        'https://gatographql.com/guides/config/configuring-the-voyager-client-for-the-single-endpoint',
                        \__('Configuring the Voyager client for the single endpoint', 'gatographql')
                    )
                ),
                Properties::TYPE => Properties::TYPE_STRING,
            ];
        }
        return $moduleSettings;
    }
}
