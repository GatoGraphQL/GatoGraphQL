<?php

declare(strict_types=1);

namespace PoPBackbone\WPDataParser\Parsers;

use PoPBackbone\WPDataParser\Exception\ParserException;

use function extension_loaded;

/**
 * WordPress Importer class for managing parsing of WXR files.
 *
 * phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
 */
class WXR_Parser
{
    /**
     * @return array<string,mixed>
     * @throws ParserException
     */
    public function parse(string $file): array
    {
        // Attempt to use proper XML parsers first
        if (extension_loaded('simplexml')) {
            $parser = new WXR_Parser_SimpleXML();
            return $parser->parse($file);
        }

        if (extension_loaded('xml')) {
            $parser = new WXR_Parser_XML();
            return $parser->parse($file);
        }

        // use regular expressions if nothing else available or this is bad XML
        $parser = new WXR_Parser_Regex();
        return $parser->parse($file);
    }
}
