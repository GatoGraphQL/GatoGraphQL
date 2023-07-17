<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL;

use GatoGraphQL\GatoGraphQL\PluginStaticHelpers;
use PHPUnit\Framework\TestCase;

class PluginStaticHelpersTest extends TestCase
{
    public function testMethods(): void
    {
        $this->assertEquals(
            PluginStaticHelpers::getResponseHeadersFromEntries(['songa']),
            ['songa' => '']
        );
    }
}
