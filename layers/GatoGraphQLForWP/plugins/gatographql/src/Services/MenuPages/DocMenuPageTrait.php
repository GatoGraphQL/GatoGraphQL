<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\Constants\RequestParams;
use GatoGraphQL\GatoGraphQL\ContentProcessors\ContentParserOptions;
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
         * Count the number of levels it goes down, and validate
         * this number is not greater than the number of levels
         * for the relative path.
         *
         * This is to improve the security, to avoid users navigating
         * out of the folder structure containing the docs.
         */
        $count = 0;
        $relativePathDirLevels = count(explode('/', $relativePathDir));

        /**
         * Move any potential "../" relative path from
         * $filename to $relativePathDir.
         *
         * Eg: Links to release-notes .md files in wp-admin/admin.php?page=gato_graphql_about
         */
        while (str_starts_with($filename, '../')) {
            $filename = substr($filename, 3);
            $relativePathDir .=  '/..';
            $count++;
        }
        if ($count > $relativePathDirLevels) {
            return sprintf(
                '<p>%s</p>',
                \__('Path is not reachable', 'gatographql')
            );
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
            $relativePathDir,
            [
                ContentParserOptions::TAB_CONTENT => $this->useTabpanelForContent(),
            ]
        ) ?? sprintf(
            '<p>%s</p>',
            sprintf(
                \__('Page \'%s\' does not exist', 'gatographql'),
                $doc
            )
        );
    }

    abstract protected function useTabpanelForContent(): bool;

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
