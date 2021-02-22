<?php

declare(strict_types=1);

namespace GraphQLAPI\MarkdownConvertor;

use Michelf\MarkdownExtra;

class MarkdownConvertor implements MarkdownConvertorInterface
{
    /**
     * Parse the file's Markdown into HTML Content
     */
    public function convertMarkdownToHTML(string $markdownContent): string
    {
        return MarkdownExtra::defaultTransform($markdownContent);
    }
}
