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
        return $parser->parse($wpDataXMLExportFile);
    }
}
