<?php

declare(strict_types=1);

namespace PoPBackbone\WPDataParser;

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
     */
    public function parse(string $wpDataXMLExportFile): array
    {
        return [];
    }
}