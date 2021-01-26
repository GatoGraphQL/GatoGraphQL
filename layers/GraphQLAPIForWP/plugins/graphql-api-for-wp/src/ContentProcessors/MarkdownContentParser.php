<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ContentProcessors;

use GraphQLAPI\MarkdownConvertor\MarkdownConvertorInterface;

class MarkdownContentParser extends AbstractContentParser
{
    protected MarkdownConvertorInterface $markdownConvertorInterface;

    function __construct(MarkdownConvertorInterface $markdownConvertorInterface)
    {
        $this->markdownConvertorInterface = $markdownConvertorInterface;
    }

    /**
     * Parse the file's Markdown into HTML Content
     */
    protected function getHTMLContent(string $fileContent): string
    {
        return $this->markdownConvertorInterface->convertMarkdownToHTML($fileContent);
    }
}
