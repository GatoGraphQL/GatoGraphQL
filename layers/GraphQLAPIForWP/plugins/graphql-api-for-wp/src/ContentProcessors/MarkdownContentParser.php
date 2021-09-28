<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ContentProcessors;

use GraphQLAPI\GraphQLAPI\Services\Helpers\LocaleHelper;
use GraphQLAPI\MarkdownConvertor\MarkdownConvertorInterface;
use PoP\ComponentModel\HelperServices\RequestHelperServiceInterface;

class MarkdownContentParser extends AbstractContentParser implements MarkdownContentParserInterface
{
    protected MarkdownConvertorInterface $markdownConvertorInterface;
    public function __construct(
        RequestHelperServiceInterface $requestHelperService,
        LocaleHelper $localeHelper,
        MarkdownConvertorInterface $markdownConvertorInterface,
        ?string $baseDir = null,
        ?string $baseURL = null,
    ) {
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
