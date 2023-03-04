<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\PROPluginInformation\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\ContentProcessors\MarkdownContentParserInterface;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\AbstractFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\PerformanceFunctionalityModuleResolverTrait;
use GraphQLAPI\GraphQLAPI\Plugin;

/**
 * The cache modules have different behavior depending on the environment:
 * - "development": visible, disabled by default
 * - "production": hidden, enabled by default
 *
 * @author Leonardo Losoviz <leo@getpop.org>
 */
class CacheFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver
{
    use ModuleResolverTrait;
    use PerformanceFunctionalityModuleResolverTrait;

    public final const CONFIGURATION_CACHE = Plugin::NAMESPACE . '\configuration-cache';
    public final const SCHEMA_INTROSPECTION_CACHE = Plugin::NAMESPACE . '\schema-introspection-cache';

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
            self::CONFIGURATION_CACHE,
            self::SCHEMA_INTROSPECTION_CACHE,
        ];
    }

    public function isHidden(string $module): bool
    {
        return match ($module) {
            /**
             * Component Model Cache is currently broken,
             * hence disable this module until fixed.
             *
             * @see https://github.com/leoloso/PoP/issues/1614
             */
            self::CONFIGURATION_CACHE => true,
            default => parent::isHidden($module),
        };
    }

    public function areRequirementsSatisfied(string $module): bool
    {
        switch ($module) {
            case self::CONFIGURATION_CACHE:
                /**
                 * Component Model Cache is currently broken,
                 * hence disable this module until fixed.
                 *
                 * @see https://github.com/leoloso/PoP/issues/1614
                 */
                return false;
        }
        return parent::areRequirementsSatisfied($module);
    }

    /**
     * @return array<string[]> List of entries that must be satisfied, each entry is an array where at least 1 module must be satisfied
     */
    public function getDependedModuleLists(string $module): array
    {
        switch ($module) {
            case self::CONFIGURATION_CACHE:
                return [];
            case self::SCHEMA_INTROSPECTION_CACHE:
                return [
                    [
                        $this->getModuleRegistry()->getInverseDependency(SchemaConfigurationFunctionalityModuleResolver::PUBLIC_PRIVATE_SCHEMA),
                    ],
                ];
        }
        return parent::getDependedModuleLists($module);
    }

    public function getName(string $module): string
    {
        return match ($module) {
            self::CONFIGURATION_CACHE => \__('Configuration Cache [PRO]', 'graphql-api-pro'),
            self::SCHEMA_INTROSPECTION_CACHE => \__('Schema Introspection Cache [PRO]', 'graphql-api-pro'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        switch ($module) {
            case self::CONFIGURATION_CACHE:
                return \__('Cache the generated application configuration to disk', 'graphql-api-pro');
            case self::SCHEMA_INTROSPECTION_CACHE:
                return \__('Cache the generated schema to disk when doing introspection', 'graphql-api-pro');
        }
        return parent::getDescription($module);
    }
}
