<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ContentProcessors;

interface ContentParserInterface
{
    /**
     * Inject the dir where to look for the documentation.
     * If null, it uses the default value from the main plugin.
     */
    public function setBaseDir(?string $baseDir = null): void;

    /**
     * Inject the URL where to look for the documentation.
     * If null, it uses the default value from the main plugin.
     */
    public function setBaseURL(?string $baseDir = null): void;

    /**
     * Parse the file's Markdown into HTML Content
     *
     * @param string $relativePathDir Dir relative to the docs/en/ folder
     * @param array<string,mixed> $options
     */
    public function getContent(
        string $filename,
        string $extension,
        string $relativePathDir = '',
        array $options = []
    ): string;

    /**
     * Default language for documentation
     */
    public function getDefaultDocsLanguage(): string;
}
