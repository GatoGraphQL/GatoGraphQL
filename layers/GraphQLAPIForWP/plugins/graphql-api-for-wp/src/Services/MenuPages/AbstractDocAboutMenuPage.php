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
            $this->enableSpecialCharsForSanitization(...)
        );

        $filename = App::query(RequestParams::DOC, '');
        $relativePathDir = $this->getRelativePathDir();

        /**
         * Move any potential "../" relative path from
         * $filename to $relativePathDir.
         *
         * Eg: Links to release-notes .md files in wp-admin/admin.php?page=graphql_api_about
         */
        while (str_starts_with($filename, '../')) {
            $filename = substr($filename, 3);
            $relativePathDir .=  '/..';
        }

        $doc = \sanitize_file_name($filename);
        remove_filter(
            'sanitize_file_name_chars',
            $this->enableSpecialCharsForSanitization(...)
        );
        return $this->getMarkdownContent(
            $doc,
            $relativePathDir
        ) ?? sprintf(
            \__('Page \'%s\' does not exist', 'graphql-api'),
            $doc
        );
    }
}
