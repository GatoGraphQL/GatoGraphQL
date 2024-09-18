<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\ContentProcessors\ContentParserOptions;
use GatoGraphQL\GatoGraphQL\ContentProcessors\MarkdownContentRetrieverTrait;
use GatoGraphQL\GatoGraphQL\Facades\Registries\ModuleRegistryFacade;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions\BundleExtensionModuleResolverInterface;

trait HasMarkdownDocumentationModuleResolverTrait
{
    use MarkdownContentRetrieverTrait;

    /**
     * The module slug
     */
    abstract public function getSlug(string $module): string;

    /**
     * The name of the Markdown filename.
     * By default, it's the same as the slug
     */
    final public function getMarkdownFilename(string $module): ?string
    {
        return $this->getSlug($module);
    }

    /**
     * Does the module have HTML Documentation?
     */
    public function hasDocumentation(string $module): bool
    {
        return !empty($this->getMarkdownFilename($module));
    }

    /**
     * HTML Documentation for the module
     */
    public function getDocumentation(string $module): ?string
    {
        $markdownFilename = $this->getMarkdownFilename($module);
        if ($markdownFilename === null || $markdownFilename === '') {
            return null;
        }

        $markdownContent = $this->getDocumentationMarkdownContent(
            $module,
            $markdownFilename,
        );
        if ($markdownContent === null) {
            return sprintf(
                '<p>%s</p>',
                \__('Oops, the documentation for this module is not available', 'gatographql')
            );
        }

        $moduleRegistry = ModuleRegistryFacade::getInstance();
        $moduleResolver = $moduleRegistry->getModuleResolver($module);
        $isBundleExtension = $moduleResolver instanceof BundleExtensionModuleResolverInterface;
        if ($isBundleExtension) {
            /** @var BundleExtensionModuleResolverInterface */
            $bundleExtensionModuleResolver = $moduleResolver;
            $bundleExtensionModules = $bundleExtensionModuleResolver->getBundledExtensionModules($module);
            foreach ($bundleExtensionModules as $bundleExtensionModule) {
                $markdownContent .= $this->getDocumentation($bundleExtensionModule);
            }
        }

        return $markdownContent;
    }

    protected function getDocumentationMarkdownContent(
        string $module,
        string $markdownFilename,
    ): ?string {
        return $this->getMarkdownContent(
            $markdownFilename,
            $this->getDocumentationMarkdownContentRelativePathDir($module),
            $this->getMarkdownContentOptions()
        );
    }

    /**
     * @return array<string,mixed>
     */
    protected function getMarkdownContentOptions(): array
    {
        return [
            ContentParserOptions::TAB_CONTENT => true,
        ];
    }

    protected function getDocumentationMarkdownContentRelativePathDir(
        string $module,
    ): string {
        return 'modules';
    }
}
