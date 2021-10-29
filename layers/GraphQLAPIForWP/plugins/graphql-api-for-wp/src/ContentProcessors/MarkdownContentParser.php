<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ContentProcessors;

use GraphQLAPI\MarkdownConvertor\MarkdownConvertorInterface;
use PoP\ComponentModel\Services\BasicServiceTrait;
use Symfony\Contracts\Service\Attribute\Required;

class MarkdownContentParser extends AbstractContentParser implements MarkdownContentParserInterface
{
    use BasicServiceTrait;

    private ?MarkdownConvertorInterface $markdownConvertor = null;

    public function setMarkdownConvertor(MarkdownConvertorInterface $markdownConvertor): void
    {
        $this->markdownConvertor = $markdownConvertor;
    }
    protected function getMarkdownConvertor(): MarkdownConvertorInterface
    {
        return $this->markdownConvertor ??= $this->instanceManager->getInstance(MarkdownConvertorInterface::class);
    }

    //#[Required]
    final public function autowireMarkdownContentParser(
        MarkdownConvertorInterface $markdownConvertor,
    ): void {
        $this->markdownConvertor = $markdownConvertor;
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
