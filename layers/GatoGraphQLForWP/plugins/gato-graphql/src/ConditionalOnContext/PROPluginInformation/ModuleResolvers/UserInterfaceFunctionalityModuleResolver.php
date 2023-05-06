<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ConditionalOnContext\PROPluginInformation\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\Plugin;
use GatoGraphQL\GatoGraphQL\ContentProcessors\MarkdownContentParserInterface;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\AbstractFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\UserInterfaceFunctionalityModuleResolverTrait;

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
                        EndpointFunctionalityModuleResolver::PERSISTED_QUERIES,
                    ],
                ];
        }
        return parent::getDependedModuleLists($module);
    }

    public function getName(string $module): string
    {
        return match ($module) {
            self::LOW_LEVEL_PERSISTED_QUERY_EDITING => \__('Low-Level Persisted Query Editing', 'gato-graphql'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        switch ($module) {
            case self::LOW_LEVEL_PERSISTED_QUERY_EDITING:
                return \__('Have access to directives to be applied to the schema when editing persisted queries', 'gato-graphql');
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
