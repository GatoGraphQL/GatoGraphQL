<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ContentProcessors;

use PoP\MarkdownConvertor\MarkdownConvertorInterface;

class MarkdownContentParser extends AbstractContentParser implements MarkdownContentParserInterface
{
    private ?MarkdownConvertorInterface $markdownConvertor = null;

    final protected function getMarkdownConvertor(): MarkdownConvertorInterface
    {
        if ($this->markdownConvertor === null) {
            /** @var MarkdownConvertorInterface */
            $markdownConvertor = $this->instanceManager->getInstance(MarkdownConvertorInterface::class);
            $this->markdownConvertor = $markdownConvertor;
        }
        return $this->markdownConvertor;
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
