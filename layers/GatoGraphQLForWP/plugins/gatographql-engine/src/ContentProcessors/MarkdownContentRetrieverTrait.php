<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ContentProcessors;

use GatoGraphQL\GatoGraphQL\Exception\ContentNotExistsException;

trait MarkdownContentRetrieverTrait
{
    abstract protected function getMarkdownContentParser(): MarkdownContentParserInterface;

    /**
     * @param array<string,mixed> $options
     */
    protected function getMarkdownContent(
        string $markdownFilename,
        string $relativePathDir = '',
        array $options = []
    ): ?string {
        $this->initializeMarkdownContentParser(
            $markdownFilename,
            $relativePathDir,
            $options,
        );
        try {
            return $this->getMarkdownContentParser()->getContent(
                $markdownFilename,
                'md',
                $relativePathDir,
                $options
            );
        } catch (ContentNotExistsException) {
            return null;
        }
    }

    /**
     * Inject the place to look for the documentation
     * @param array<string,mixed> $options
     */
    protected function initializeMarkdownContentParser(
        string $markdownFilename,
        string $relativePathDir = '',
        array $options = []
    ): void {
        $markdownContentParser = $this->getMarkdownContentParser();
        $markdownContentParser->setBaseDir($this->getBaseDir());
        $markdownContentParser->setBaseURL($this->getBaseURL());
        $markdownContentParser->setDocsFolder($this->getDocsFolder());
        $markdownContentParser->setGitHubRepoDocsRootPathURL($this->getGitHubRepoDocsRootPathURL());
        $markdownContentParser->setUseDocsFolderInFileDir($this->getUseDocsFolderInFileDir());
    }

    /**
     * Get the dir where to look for the documentation.
     */
    abstract protected function getBaseDir(): string;

    /**
     * Get the URL where to look for the documentation.
     */
    abstract protected function getBaseURL(): string;

    /**
     * Get the folder under which the docs are stored
     */
    abstract protected function getDocsFolder(): string;

    /**
     * Get the GitHub repo URL, to retrieve images for PROD.
     */
    abstract protected function getGitHubRepoDocsRootPathURL(): string;

    /**
     * Use `false` to pass the "docs" folder when requesting
     * the file to read (so can retrieve files from either
     * "docs" or "docs-extensions" folders)
     */
    abstract protected function getUseDocsFolderInFileDir(): bool;
}
