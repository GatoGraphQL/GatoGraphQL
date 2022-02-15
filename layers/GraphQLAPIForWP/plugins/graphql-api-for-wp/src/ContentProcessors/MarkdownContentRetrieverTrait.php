<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ContentProcessors;

use GraphQLAPI\GraphQLAPI\Exception\PluginBackendException;

trait MarkdownContentRetrieverTrait
{
    abstract protected function getMarkdownContentParser(): MarkdownContentParserInterface;

    /**
     * @param array<string, mixed> $options
     */
    public function getMarkdownContent(
        string $markdownFilename,
        string $relativePathDir = '',
        array $options = []
    ): ?string {
        // Inject the place to look for the documentation
        $this->getMarkdownContentParser()->setBaseDir($this->getBaseDir());
        $this->getMarkdownContentParser()->setBaseURL($this->getBaseURL());
        try {
            return $this->getMarkdownContentParser()->getContent(
                $markdownFilename,
                $relativePathDir,
                $options
            );
        } catch (PluginBackendException) {
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
