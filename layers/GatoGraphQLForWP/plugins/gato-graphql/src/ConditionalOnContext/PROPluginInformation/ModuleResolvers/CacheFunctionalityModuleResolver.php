<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ConditionalOnContext\PROPluginInformation\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\ContentProcessors\MarkdownContentParserInterface;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\AbstractFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\PerformanceFunctionalityModuleResolverTrait;
use GatoGraphQL\GatoGraphQL\Plugin;

class CacheFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver implements PROPseudoModuleResolverInterface
{
    use ModuleResolverTrait {
        isPredefinedEnabledOrDisabled as upstreamIsPredefinedEnabledOrDisabled;
    }
    use PerformanceFunctionalityModuleResolverTrait;

    public final const CONFIGURATION_CACHE = Plugin::NAMESPACE . '\configuration-cache';

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
        }
        return parent::getDependedModuleLists($module);
    }

    public function getName(string $module): string
    {
        return match ($module) {
            self::CONFIGURATION_CACHE => \__('Configuration Cache', 'gato-graphql'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        switch ($module) {
            case self::CONFIGURATION_CACHE:
                return \__('Cache the generated application configuration to disk', 'gato-graphql');
        }
        return parent::getDescription($module);
    }
}
