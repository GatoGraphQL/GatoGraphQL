<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ContentProcessors;

use GraphQLAPI\MarkdownConvertor\MarkdownConvertorInterface;

class MarkdownContentParser extends AbstractContentParser implements MarkdownContentParserInterface
{
    private ?MarkdownConvertorInterface $markdownConvertor = null;

    final public function setMarkdownConvertor(MarkdownConvertorInterface $markdownConvertor): void
    {
        $this->markdownConvertor = $markdownConvertor;
    }
    final protected function getMarkdownConvertor(): MarkdownConvertorInterface
    {
        return $this->markdownConvertor ??= $this->instanceManager->getInstance(MarkdownConvertorInterface::class);
    }

    /**
     * Parse the file's Markdown into HTML Content
     */
    protected function getHTMLContent(string $fileContent): string
    {
        return $this->convertMarkdownToHTML($fileContent);
    }

    /**
     * Parse the file's Markdown into HTML Content
     */
    public function convertMarkdownToHTML(string $markdownContent): string
    {
        return $this->getMarkdownConvertor()->convertMarkdownToHTML($markdownContent);
    }
}
