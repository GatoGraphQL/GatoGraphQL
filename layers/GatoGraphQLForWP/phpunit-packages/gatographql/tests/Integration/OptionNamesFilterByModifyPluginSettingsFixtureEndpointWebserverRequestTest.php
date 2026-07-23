<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

class OptionNamesFilterByModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractOptionsModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    protected static function getEndpoint(): string
    {
        return 'graphql';
    }

    protected static function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-option-names-filter-by';
    }

    /**
     * @return string[]
     */
    protected function getAllowedOptionEntries(): array
    {
        return [
            'blogname',
            'blogdescription',
            'siteurl',
            'home',
            'template',
            'stylesheet',
            'date_format',
            'posts_per_page',
        ];
    }
}
