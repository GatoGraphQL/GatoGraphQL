<?php

declare(strict_types=1);

namespace GraphQLAPI\MarkdownConvertor;

use Parsedown;

class MarkdownConvertor implements MarkdownConvertorInterface
{
    /**
     * Parse the file's Markdown into HTML Content
     */
    public function convertMarkdownToHTML(string $markdownContent): string
    {
        return (new Parsedown())->text($markdownContent);
    }
}
