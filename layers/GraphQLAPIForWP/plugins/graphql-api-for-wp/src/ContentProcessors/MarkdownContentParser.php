<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ContentProcessors;

use Symfony\Contracts\Service\Attribute\Required;
use GraphQLAPI\GraphQLAPI\Services\Helpers\LocaleHelper;
use GraphQLAPI\MarkdownConvertor\MarkdownConvertorInterface;
use PoP\ComponentModel\HelperServices\RequestHelperServiceInterface;

class MarkdownContentParser extends AbstractContentParser implements MarkdownContentParserInterface
{
    protected MarkdownConvertorInterface $markdownConvertorInterface;

    #[Required]
    public function autowireMarkdownContentParser(
        MarkdownConvertorInterface $markdownConvertorInterface,
    ): void {
        $this->markdownConvertorInterface = $markdownConvertorInterface;
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
        return $this->markdownConvertorInterface->convertMarkdownToHTML($markdownContent);
    }
}
