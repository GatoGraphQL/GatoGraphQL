<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\ContentProcessors\MarkdownContentParserInterface;
use GatoGraphQL\GatoGraphQL\Plugin;

class EndpointConfigurationFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver
{
    use ModuleResolverTrait;
    use EndpointConfigurationFunctionalityModuleResolverTrait;

    public final const API_HIERARCHY = Plugin::NAMESPACE . '\api-hierarchy';

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
            self::API_HIERARCHY,
        ];
    }

    /**
     * @return array<string[]> List of entries that must be satisfied, each entry is an array where at least 1 module must be satisfied
     */
    public function getDependedModuleLists(string $module): array
    {
        switch ($module) {
            case self::API_HIERARCHY:
                return [
                    [
                        EndpointFunctionalityModuleResolver::CUSTOM_ENDPOINTS,
                        EndpointFunctionalityModuleResolver::PERSISTED_QUERIES,
                    ],
                ];
        }
        return parent::getDependedModuleLists($module);
    }

    public function getName(string $module): string
    {
        return match ($module) {
            self::API_HIERARCHY => \__('API Hierarchy', 'gato-graphql'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        switch ($module) {
            case self::API_HIERARCHY:
                return \__('Create a hierarchy of API endpoints extending from other endpoints, and inheriting their properties', 'gato-graphql');
        }
        return parent::getDescription($module);
    }
}
