<?php

declare(strict_types=1);

namespace PoPBackbone\WPDataParser;

use PHPUnit\Framework\TestCase;
use PoPBackbone\WPDataParser\Exception\ParserException;

class ParserTest extends TestCase
{
    public function testParser(): void
    {
        $wpDataXMLExportFile = __DIR__ . '/Resources/sample-data.wordpress.xml';
        $wpDataParser = new WPDataParser();
        $parsedData = $wpDataParser->parse($wpDataXMLExportFile);
        $this->assertEquals(
            require __DIR__ . '/Resources/parsed-sample-data-expected-response.php',
            $parsedData
        );
    }

    public function testInvalidFile(): void
    {
        $this->expectException(ParserException::class);
        $wpDataXMLExportFile = __DIR__ . '/Resources/invalid.wordpress.xml';
        $wpDataParser = new WPDataParser();
        $parsedData = $wpDataParser->parse($wpDataXMLExportFile);
    }

    public function testNonExistingFile(): void
    {
        $this->expectException(ParserException::class);
        $wpDataXMLExportFile = __DIR__ . '/Resources/non-existing.wordpress.xml';
        $wpDataParser = new WPDataParser();
        $parsedData = $wpDataParser->parse($wpDataXMLExportFile);
    }
}
