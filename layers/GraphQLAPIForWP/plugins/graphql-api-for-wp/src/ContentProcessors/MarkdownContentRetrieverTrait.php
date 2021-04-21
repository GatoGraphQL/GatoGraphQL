<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ContentProcessors;

use InvalidArgumentException;
use GraphQLAPI\GraphQLAPI\Facades\ContentProcessors\MarkdownContentParserFacade;

trait MarkdownContentRetrieverTrait
{
    public function getMarkdownContent(
        string $markdownFilename,
        string $relativePathDir = '',
        array $options = [],
        ?string $errorMessage = null
    ): ?string {
        $markdownContentParser = MarkdownContentParserFacade::getInstance();
        // Inject the place to look for the documentation
        $markdownContentParser->setBaseDir($this->getBaseDir());
        $markdownContentParser->setBaseURL($this->getBaseURL());
        try {
            return $markdownContentParser->getContent(
                $markdownFilename,
                $relativePathDir,
                $options
            );
        } catch (InvalidArgumentException) {
            $errorMessage ??= sprintf(
                \__('Oops, there was a problem retrieving content from file \'%s\'', 'graphql-api'),
                $markdownFilename
            );
            return sprintf(
                '<p>%s</p>',
                $errorMessage
            );
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
