<?php

declare(strict_types=1);

namespace PoPBackbone\WPDataParser;

use PoPBackbone\WPDataParser\Exception\ParserException;
use PoPBackbone\WPDataParser\Parsers\WXR_Parser;

/**
 * This is a fork of the WordPress Importer plugin, adapted for PoP
 *
 * @see https://wordpress.org/plugins/wordpress-importer/
 */
class WPDataParser
{
    /**
     * Parse the WordPress export file and return the data
     *
     * @return array<string,mixed>
     * @throws ParserException
     */
    public function parse(string $wpDataXMLExportFile): array
    {
        if (!file_exists($wpDataXMLExportFile)) {
            throw new ParserException(sprintf(
                'WordPress expord data file "%s" does not exist',
                $wpDataXMLExportFile
            ));
        }
        $parser = new WXR_Parser();
        return $this->customDataTransformations(
            $parser->parse($wpDataXMLExportFile),
            $wpDataXMLExportFile
        );
    }

    /**
     * Modifications applied on the parsed data.
     *
     * Because the WordPress export file does not assign IDs to
     * the authors, posts have a relationship to the author
     * via the nicename, not the ID.
     *
     * However, in this upgraded version we do already have
     * the author ID, then replace it.
     *
     * This fixes the issue of querying ```{ posts { author { id }}}```
     * which fails since post_author expects the ID in the resolver.
     *
     * Do the same for comments, but if the author does not exist,
     * it's not an error, since comments can be added by non-users too.
     *
     * @param array<string,mixed> $data
     * @return array<string,mixed>
     * @throws ParserException If the author ID has not been added to the dataset file
     */
    protected function customDataTransformations(
        array $data,
        string $wpDataXMLExportFile,
    ): array {
        $authorDataEntries = $data['authors'] ?? [];
        $postDataEntries = $data['posts'] ?? [];
        foreach ($postDataEntries as $key => $postDataEntry) {
            // Find the post author with the given nicename
            $authorDataEntry = $authorDataEntries[$postDataEntry['post_author']] ?? null;
            if ($authorDataEntry === null) {
                throw new ParserException(sprintf(
                    'The ID for author "%s" has not beed added to dataset file "%s"',
                    $postDataEntry['post_author'],
                    $wpDataXMLExportFile
                ));
            }
            // Replace the nicename with the ID
            $data['posts'][$key]['post_author_login'] = $data['posts'][$key]['post_author'];
            $data['posts'][$key]['post_author'] = $authorDataEntry['author_id'];

            // Convert comments
            $postCommentDataEntries = $postDataEntry['comments'] ?? [];
            foreach ($postCommentDataEntries as $commentKey => $postCommentDataEntry) {
                // Find the comment author with the given nicename
                $authorDataEntry = $authorDataEntries[$postCommentDataEntry['comment_author']] ?? null;
                if ($authorDataEntry === null) {
                    continue;
                }
                // Replace current value `0` with the user ID
                $data['posts'][$key]['comments'][$commentKey]['comment_user_id'] = $authorDataEntry['author_id'];
            }
        }
        return $data;
    }
}
