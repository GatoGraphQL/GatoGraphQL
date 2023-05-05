<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ContentProcessors;

interface MarkdownContentParserInterface extends ContentParserInterface
{
    /**
     * Parse the file's Markdown into HTML Content
     */
    public function convertMarkdownToHTML(string $markdownContent): string;
}
