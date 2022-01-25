<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPages;

use PoP\Root\App;
use GraphQLAPI\GraphQLAPI\Constants\RequestParams;

/**
 * Open documentation within the About page
 */
abstract class AbstractDocAboutMenuPage extends AbstractDocsMenuPage
{
    protected function openInModalWindow(): bool
    {
        return true;
    }

    /**
     * Enable "/" in the filename
     *
     * @param string[] $specialChars
     * @return string[]
     */
    public function enableSpecialCharsForSanitization(array $specialChars): array
    {
        return array_diff(
            $specialChars,
            [
                '/',
            ]
        );
    }

    protected function getRelativePathDir(): string
    {
        return '';
    }

    protected function getContentToPrint(): string
    {
        // Enable "/" in the filename
        add_filter(
            'sanitize_file_name_chars',
            [$this, 'enableSpecialCharsForSanitization']
        );
        $filename = App::query(RequestParams::DOC, '');
        $doc = \sanitize_file_name($filename . '.md');
        remove_filter(
            'sanitize_file_name_chars',
            [$this, 'enableSpecialCharsForSanitization']
        );
        return $this->getMarkdownContent(
            $doc,
            $this->getRelativePathDir()
        ) ?? sprintf(
            \__('Page \'%s\' does not exist', 'graphql-api'),
            $doc
        );
    }
}
