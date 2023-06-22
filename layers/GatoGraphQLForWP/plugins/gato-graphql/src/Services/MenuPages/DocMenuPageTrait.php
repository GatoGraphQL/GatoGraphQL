<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\Constants\RequestParams;
use PoP\Root\App;

/**
 * Open a document provided via ?doc=...
 */
trait DocMenuPageTrait
{
    protected function getRelativePathDir(): string
    {
        return '';
    }

    protected function getDocumentationContentToPrint(): string
    {
        /** @var string */
        $filename = App::query(RequestParams::DOC, '');
        $relativePathDir = $this->getRelativePathDir();

        /**
         * Move any potential "../" relative path from
         * $filename to $relativePathDir.
         *
         * Eg: Links to release-notes .md files in wp-admin/admin.php?page=gato_graphql_about
         */
        while (str_starts_with($filename, '../')) {
            $filename = substr($filename, 3);
            $relativePathDir .=  '/..';
        }

        // Enable "/" in the filename
        add_filter(
            'sanitize_file_name_chars',
            $this->enableSpecialCharsForSanitization(...)
        );
        $doc = \sanitize_file_name($filename);
        remove_filter(
            'sanitize_file_name_chars',
            $this->enableSpecialCharsForSanitization(...)
        );
        return $this->getMarkdownContent(
            $doc,
            $relativePathDir
        ) ?? sprintf(
            '<p>%s</p>',
            sprintf(
                \__('Page \'%s\' does not exist', 'gato-graphql'),
                $doc
            )
        );
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

    abstract protected function getMarkdownContent(
        string $markdownFilename,
        string $relativePathDir = '',
        array $options = []
    ): ?string;
}
