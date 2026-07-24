<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

class OptionsModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractOptionsModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    protected static function getEndpoint(): string
    {
        return 'graphql';
    }

    protected static function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-options';
    }

    /**
     * @return string[]
     */
    protected function getAllowedOptionEntries(): array
    {
        $dataName = $this->getDataName();
        if (str_ends_with($dataName, ':1')) {
            return ['siteurl', 'home'];
        }
        return ['blogname', 'blogdescription', 'siteurl', 'home'];
    }
}
