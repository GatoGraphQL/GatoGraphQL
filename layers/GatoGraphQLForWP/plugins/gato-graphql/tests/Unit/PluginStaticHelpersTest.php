<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL;

use GatoGraphQL\GatoGraphQL\PluginStaticHelpers;
use PHPUnit\Framework\TestCase;

class PluginStaticHelpersTest extends TestCase
{
    /**
     * @param string[] $entries
     * @param array<string,string> $responseHeaders
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('provideGetResponseHeadersFromEntries')]
    public function testGetResponseHeadersFromEntries(array $entries, array $responseHeaders): void
    {
        $this->assertEquals(
            PluginStaticHelpers::getResponseHeadersFromEntries($entries),
            $responseHeaders
        );
    }

    public static function provideGetResponseHeadersFromEntries(): array
    {
        return [
            'empty' => [
                [''],
                [],
            ],
            'only-whitespaces' => [
                ['     '],
                [],
            ],
            'only-whitespaces-in-two-sides' => [
                ['     :      '],
                [],
            ],
            'only-separator' => [
                [':'],
                [],
            ],
            'only-header-name' => [
                ['Access-Control-Allow-Origin'],
                ['Access-Control-Allow-Origin' => ''],
            ],
            'header-and-value' => [
                ['Access-Control-Allow-Origin: null'],
                ['Access-Control-Allow-Origin' => 'null'],
            ],
            'trimming-whitespaces' => [
                [' Access-Control-Allow-Headers   :    content-type,content-length,accept   '],
                ['Access-Control-Allow-Headers' => 'content-type,content-length,accept'],
            ],
            'more-than-one-separator' => [
                ['X-Custom-Label: This is my idea: Nothing!'],
                ['X-Custom-Label' => 'This is my idea: Nothing!'],
            ],
            'with-quotes' => [
                ['X-Custom-Label: "This is my idea: Nothing!"'],
                ['X-Custom-Label' => '"This is my idea: Nothing!"'],
            ],
            'with-quotes-2' => [
                ['X-Custom-Label: \'This is my idea: Nothing!\''],
                ['X-Custom-Label' => '\'This is my idea: Nothing!\''],
            ],
            'many-items' => [
                [
                    ':',
                    'Access-Control-Allow-Origin',
                    ' Access-Control-Allow-Headers   :    content-type,content-length,accept   ',
                    'X-Custom-Label: This is my idea: Nothing!',
                ],
                [
                    'Access-Control-Allow-Origin' => '',
                    'Access-Control-Allow-Headers' => 'content-type,content-length,accept',
                    'X-Custom-Label' => 'This is my idea: Nothing!',
                ],
            ],
        ];
    }
}
