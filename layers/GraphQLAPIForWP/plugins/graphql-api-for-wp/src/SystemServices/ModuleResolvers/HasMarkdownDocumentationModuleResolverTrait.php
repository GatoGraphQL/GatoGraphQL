<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\SystemServices\ModuleResolvers;

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
     * @param string $module
     * @return string
     */
    public function getMarkdownFilename(string $module): ?string
    {
        return $this->getSlug($module) . '.md';
    }

    /**
     * Does the module have HTML Documentation?
     *
     * @param string $module
     * @return bool
     */
    public function hasDocumentation(string $module): bool
    {
        return !empty($this->getMarkdownFilename($module));
    }

    /**
     * HTML Documentation for the module
     *
     * @param string $module
     * @return string|null
     */
    public function getDocumentation(string $module): ?string
    {
        if ($markdownFilename = $this->getMarkdownFilename($module)) {
            $markdownContentParser = MarkdownContentParserFacade::getInstance();
            try {
                return $markdownContentParser->getContent(
                    'modules/' . $markdownFilename,
                    'modules',
                    [
                        ContentParserOptions::TAB_CONTENT => true,
                    ]
                );
            } catch (InvalidArgumentException $e) {
                return sprintf(
                    '<p>%s</p>',
                    \__('Oops, the documentation for this module is not available', 'graphql-api')
                );
            }
        }
        return null;
    }
}
