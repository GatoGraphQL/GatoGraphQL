<?php

declare(strict_types=1);

namespace PoP\MarkdownConvertor;

interface MarkdownConvertorInterface
{
    /**
     * Parse the file's Markdown into HTML Content
     */
    public function convertMarkdownToHTML(string $markdownContent): string;
}
