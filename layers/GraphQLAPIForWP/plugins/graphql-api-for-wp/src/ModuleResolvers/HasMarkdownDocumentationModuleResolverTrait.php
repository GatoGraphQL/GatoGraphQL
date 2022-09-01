<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\ContentProcessors\ContentParserOptions;
use GraphQLAPI\GraphQLAPI\ContentProcessors\MarkdownContentRetrieverTrait;

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
        if ($markdownFilename = $this->getMarkdownFilename($module)) {
            return $this->getMarkdownContent(
                'modules/' . $markdownFilename,
                'modules',
                [
                    ContentParserOptions::TAB_CONTENT => true,
                ]
            ) ?? sprintf(
                '<p>%s</p>',
                \__('Oops, the documentation for this module is not available', 'graphql-api')
            );
        }
        return null;
    }
}
