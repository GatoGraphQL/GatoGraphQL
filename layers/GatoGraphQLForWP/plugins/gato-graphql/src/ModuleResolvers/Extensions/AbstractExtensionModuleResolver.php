<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions;

use GatoGraphQL\GatoGraphQL\ContentProcessors\MarkdownContentParserInterface;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\AbstractModuleResolver;

/**
 * Container modules to display documentation for extensions
 * in the Extensions page.
 */
abstract class AbstractExtensionModuleResolver extends AbstractModuleResolver
{
    use ExtensionModuleResolverTrait;

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
     * The type of the module doesn't matter, as these modules
     * are all hidden anyway
     */
    public function getModuleType(string $module): string
    {
        return '';
    }

    public function isHidden(string $module): bool
    {
        return true;
    }

    public function isPredefinedEnabledOrDisabled(string $module): ?bool
    {
        return true;
    }

    public function getGatoGraphQLExtensionSlug(string $module): string
    {
        return 'gato-graphql-' . $this->getSlug($module);
    }

    protected function getDocumentationMarkdownContentRelativePathDir(
        string $module,
    ): ?string {
        return $this->getSlug($module) . '/docs/modules';
    }
}
