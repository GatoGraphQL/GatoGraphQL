<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\ContentProcessors\ContentParserOptions;
use GatoGraphQL\GatoGraphQL\ContentProcessors\MarkdownContentRetrieverTrait;

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

        return $this->getDocumentationMarkdownContent(
            $module,
            $markdownFilename,
        ) ?? sprintf(
            '<p>%s</p>',
            \__('Oops, the documentation for this module is not available', 'gatographql')
        );
    }

    protected function getDocumentationMarkdownContent(
        string $module,
        string $markdownFilename,
    ): ?string {
        return $this->getMarkdownContent(
            $markdownFilename,
            $this->getDocumentationMarkdownContentRelativePathDir($module),
            $this->getMarkdownContentOptions($module)
        );
    }

    /**
     * @return array<string,mixed>
     */
    protected function getMarkdownContentOptions(string $module): array
    {
        $options = [
            ContentParserOptions::TAB_CONTENT => true,
        ];
        $websiteURL = $this->getDocumentationWebsiteURL($module);
        if ($websiteURL !== null) {
            $options[ContentParserOptions::WEBSITE_DOC_URL] = $websiteURL;
        }
        return $options;
    }

    /**
     * The doc's canonical page URL on the website, or null when not available.
     * When set, the English-doc notice links straight to it (injecting the user's
     * language subdomain) instead of deriving the path from the local docs layout.
     */
    protected function getDocumentationWebsiteURL(string $module): ?string
    {
        return null;
    }

    protected function getDocumentationMarkdownContentRelativePathDir(
        string $module,
    ): string {
        return 'modules';
    }
}
