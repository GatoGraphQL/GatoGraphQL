<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ContentProcessors;

use GraphQLAPI\GraphQLAPI\Facades\ContentProcessors\MarkdownContentParserFacade;
use InvalidArgumentException;

trait MarkdownContentRetrieverTrait
{
    /**
     * @param array<string, mixed> $options
     */
    public function getMarkdownContent(
        string $markdownFilename,
        string $relativePathDir = '',
        array $options = []
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
            return null;
        }
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
