<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\ContentProcessors\MarkdownContentParserInterface;
use GraphQLAPI\GraphQLAPI\ModuleSettings\Properties;
use GraphQLAPI\GraphQLAPI\Plugin;

/**
 * Deprecated because the GraphiQL Explorer options are not displayed anymore.
 *
 * This module will be removed once GraphiQL v3.0, with the GraphiQL Explorer
 * already integrated, is released.
 *
 * @see https://github.com/leoloso/PoP/issues/1902
 */
class DeprecatedClientFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver
{
    use ModuleResolverTrait;
    use ClientFunctionalityModuleResolverTrait;

    public final const GRAPHIQL_EXPLORER = Plugin::NAMESPACE . '\graphiql-explorer';

    /**
     * Setting options
     */
    public final const OPTION_USE_IN_ADMIN_CLIENT = 'use-in-admin-client';
    public final const OPTION_USE_IN_ADMIN_PERSISTED_QUERIES = 'use-in-admin-persisted-queries';
    public final const OPTION_USE_IN_PUBLIC_CLIENT_FOR_SINGLE_ENDPOINT = 'use-in-public-client-for-single-endpoint';
    public final const OPTION_USE_IN_PUBLIC_CLIENT_FOR_CUSTOM_ENDPOINTS = 'use-in-public-client-for-custom-endpoints';

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
            self::GRAPHIQL_EXPLORER,
        ];
    }

    /**
     * @return array<string[]> List of entries that must be satisfied, each entry is an array where at least 1 module must be satisfied
     */
    public function getDependedModuleLists(string $module): array
    {
        switch ($module) {
            case self::GRAPHIQL_EXPLORER:
                return [];
        }
        return parent::getDependedModuleLists($module);
    }

    public function getName(string $module): string
    {
        return match ($module) {
            self::GRAPHIQL_EXPLORER => \__('GraphiQL Explorer', 'graphql-api'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        switch ($module) {
            case self::GRAPHIQL_EXPLORER:
                return \__('Add the Explorer widget to the GraphiQL client, to simplify coding the query (by point-and-clicking on the fields)', 'graphql-api');
        }
        return parent::getDescription($module);
    }

    /**
     * Default value for an option set by the module
     */
    public function getSettingsDefaultValue(string $module, string $option): mixed
    {
        $defaultValues = [
            self::GRAPHIQL_EXPLORER => [
                self::OPTION_USE_IN_ADMIN_CLIENT => true,
                self::OPTION_USE_IN_ADMIN_PERSISTED_QUERIES => true,
                self::OPTION_USE_IN_PUBLIC_CLIENT_FOR_SINGLE_ENDPOINT => true,
                self::OPTION_USE_IN_PUBLIC_CLIENT_FOR_CUSTOM_ENDPOINTS => true,
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
        if ($module === self::GRAPHIQL_EXPLORER) {
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
            if (
                $this->getModuleRegistry()->isModuleEnabled(EndpointFunctionalityModuleResolver::PUBLIC_PERSISTED_QUERIES)
                || $this->getModuleRegistry()->isModuleEnabled(EndpointFunctionalityModuleResolver::PRIVATE_PERSISTED_QUERIES)
            ) {
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
            if ($this->getModuleRegistry()->isModuleEnabled(ClientFunctionalityModuleResolver::GRAPHIQL_FOR_SINGLE_ENDPOINT)) {
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
            if ($this->getModuleRegistry()->isModuleEnabled(ClientFunctionalityModuleResolver::GRAPHIQL_FOR_CUSTOM_ENDPOINTS)) {
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

    /**
     * Because GraphiQL v2.0 (yet to be integrated) has the
     * Explorer already built-in, there's no need to have a
     * separate module for it.
     *
     * Since this functionality is already built and working,
     * simply hide it.
     *
     * @see https://github.com/leoloso/PoP/issues/1902
     */
    public function isHidden(string $module): bool
    {
        return match ($module) {
            self::GRAPHIQL_EXPLORER => true,
            default => parent::isHidden($module),
        };
    }

    /**
     * Because GraphiQL v2.0 (yet to be integrated) has the
     * Explorer already built-in, there's no need to have a
     * separate module for it.
     *
     * Since this functionality is already built and working,
     * simply hide it.
     *
     * @see https://github.com/leoloso/PoP/issues/1902
     */
    public function areSettingsHidden(string $module): bool
    {
        return match ($module) {
            self::GRAPHIQL_EXPLORER => true,
            default => parent::areSettingsHidden($module),
        };
    }
}
