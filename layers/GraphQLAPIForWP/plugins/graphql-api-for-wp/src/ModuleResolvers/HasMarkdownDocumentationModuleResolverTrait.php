<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\ContentProcessors\ContentParserOptions;
use InvalidArgumentException;
use GraphQLAPI\GraphQLAPI\Facades\ContentProcessors\MarkdownContentParserFacade;

trait HasMarkdownDocumentationModuleResolverTrait
{
    /**
     * The module slug
     */
    abstract public function getSlug(string $module): string;

    /**
     * The name of the Markdown filename.
     * By default, it's the same as the slug
     *
     * @return string
     */
    public function getMarkdownFilename(string $module): ?string
    {
        return $this->getSlug($module) . '.md';
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
            $markdownContentParser = MarkdownContentParserFacade::getInstance();
            // Inject the place to look for the documentation
            $markdownContentParser->setBaseDir($this->getBaseDir());
            $markdownContentParser->setBaseURL($this->getBaseURL());
            try {
                return $markdownContentParser->getContent(
                    'modules/' . $markdownFilename,
                    'modules',
                    [
                        ContentParserOptions::TAB_CONTENT => true,
                    ]
                );
            } catch (InvalidArgumentException) {
                return sprintf(
                    '<p>%s</p>',
                    \__('Oops, the documentation for this module is not available', 'graphql-api')
                );
            }
        }
        return null;
    }

    /**
     * Get the dir where to look for the documentation.
     */
    abstract protected function getBaseDir(): string;

    /**
     * Get the URL where to look for the documentation.
     */
    abstract protected function getBaseURL(): string;
}
