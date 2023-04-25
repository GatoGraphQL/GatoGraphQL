<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\PROPluginInformation\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\Plugin;
use GraphQLAPI\GraphQLAPI\ContentProcessors\MarkdownContentParserInterface;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\AbstractFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\UserInterfaceFunctionalityModuleResolverTrait;

class UserInterfaceFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver implements PROPseudoModuleResolverInterface
{
    use ModuleResolverTrait {
        ModuleResolverTrait::isPredefinedEnabledOrDisabled as upstreamIsPredefinedEnabledOrDisabled;
    }
    use UserInterfaceFunctionalityModuleResolverTrait;

    public final const LOW_LEVEL_PERSISTED_QUERY_EDITING = Plugin::NAMESPACE . '\low-level-persisted-query-editing';

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
            self::LOW_LEVEL_PERSISTED_QUERY_EDITING,
        ];
    }

    /**
     * @return array<string[]> List of entries that must be satisfied, each entry is an array where at least 1 module must be satisfied
     */
    public function getDependedModuleLists(string $module): array
    {
        switch ($module) {
            case self::LOW_LEVEL_PERSISTED_QUERY_EDITING:
                return [
                    [
                        EndpointFunctionalityModuleResolver::PUBLIC_PERSISTED_QUERIES,
                    ],
                ];
        }
        return parent::getDependedModuleLists($module);
    }

    public function getName(string $module): string
    {
        return match ($module) {
            self::LOW_LEVEL_PERSISTED_QUERY_EDITING => \__('Low-Level Persisted Query Editing', 'graphql-api'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        switch ($module) {
            case self::LOW_LEVEL_PERSISTED_QUERY_EDITING:
                return \__('Have access to directives to be applied to the schema when editing persisted queries', 'graphql-api');
        }
        return parent::getDescription($module);
    }

    public function isEnabledByDefault(string $module): bool
    {
        switch ($module) {
            case self::LOW_LEVEL_PERSISTED_QUERY_EDITING:
                return false;
        }
        return parent::isEnabledByDefault($module);
    }

    public function isPredefinedEnabledOrDisabled(string $module): ?bool
    {
        switch ($module) {
            case self::LOW_LEVEL_PERSISTED_QUERY_EDITING:
                return false;
        }
        return $this->upstreamIsPredefinedEnabledOrDisabled($module);
    }
}
