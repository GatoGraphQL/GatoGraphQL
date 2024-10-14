<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\ContentProcessors\MarkdownContentParserInterface;
use GatoGraphQL\GatoGraphQL\Plugin;
use GatoGraphQL\GatoGraphQL\Services\CustomPostTypes\CustomPostTypeInterface;

class UserInterfaceFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver
{
    use ModuleResolverTrait;
    use UserInterfaceFunctionalityModuleResolverTrait;

    public final const WELCOME_GUIDES = Plugin::NAMESPACE . '\welcome-guides';

    /** @var CustomPostTypeInterface[] */
    protected ?array $useExcerptAsDescriptionEnabledCustomPostTypeServices = null;

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
            self::WELCOME_GUIDES,
        ];
    }

    /**
     * @return array<string[]> List of entries that must be satisfied, each entry is an array where at least 1 module must be satisfied
     */
    public function getDependedModuleLists(string $module): array
    {
        switch ($module) {
            case self::WELCOME_GUIDES:
                return [];
        }
        return parent::getDependedModuleLists($module);
    }

    public function areRequirementsSatisfied(string $module): bool
    {
        switch ($module) {
            case self::WELCOME_GUIDES:
                /**
                 * WordPress 5.5 or above, or Gutenberg 8.2 or above
                 */
                return
                    \is_wp_version_compatible('5.5') ||
                    (
                        defined('GUTENBERG_VERSION') &&
                        \version_compare(constant('GUTENBERG_VERSION'), '8.2', '>=')
                    );
        }
        return parent::areRequirementsSatisfied($module);
    }

    public function isHidden(string $module): bool
    {
        switch ($module) {
            case self::WELCOME_GUIDES:
                return true;
        }
        return parent::isHidden($module);
    }

    public function getName(string $module): string
    {
        return match ($module) {
            self::WELCOME_GUIDES => \__('Welcome Guides', 'gatographql'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        return match ($module) {
            self::WELCOME_GUIDES => sprintf(
                \__('Display welcome guides which demonstrate how to use the plugin\'s different functionalities. <em>It requires WordPress version \'%s\' or above, or Gutenberg version \'%s\' or above</em>', 'gatographql'),
                '5.5',
                '8.2'
            ),
            default => parent::getDescription($module),
        };
    }

    public function isPredefinedEnabledOrDisabled(string $module): ?bool
    {
        return match ($module) {
            self::WELCOME_GUIDES => false,
            default => parent::isPredefinedEnabledOrDisabled($module),
        };
    }
}
