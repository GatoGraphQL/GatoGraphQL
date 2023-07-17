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
            'only-header-name' => [
                ['songa'],
                ['songa' => ''],
            ],
        ];
    }
}
