<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ConditionalOnContext\PROPluginInformation\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\ContentProcessors\MarkdownContentParserInterface;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\AbstractFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\PerformanceFunctionalityModuleResolverTrait;
use GatoGraphQL\GatoGraphQL\Plugin;

class PerformanceFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver implements PROPseudoModuleResolverInterface
{
    use ModuleResolverTrait;
    use PerformanceFunctionalityModuleResolverTrait;

    public final const CACHE_CONTROL = Plugin::NAMESPACE . '\cache-control';

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
            self::CACHE_CONTROL,
        ];
    }

    /**
     * @return array<string[]> List of entries that must be satisfied, each entry is an array where at least 1 module must be satisfied
     */
    public function getDependedModuleLists(string $module): array
    {
        switch ($module) {
            case self::CACHE_CONTROL:
                return [];
        }
        return parent::getDependedModuleLists($module);
    }

    public function getName(string $module): string
    {
        return match ($module) {
            self::CACHE_CONTROL => \__('Cache Control', 'gato-graphql'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        switch ($module) {
            case self::CACHE_CONTROL:
                return \__('Provide HTTP Caching (for Persisted Queries or endpoints accessed via GET), sending the Cache-Control header with a max-age value calculated from all fields in the query', 'gato-graphql');
        }
        return parent::getDescription($module);
    }
}
