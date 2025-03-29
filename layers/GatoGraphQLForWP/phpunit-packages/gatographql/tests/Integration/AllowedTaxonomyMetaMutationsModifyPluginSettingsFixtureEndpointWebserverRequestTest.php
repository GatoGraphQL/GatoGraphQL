<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

class AllowedTaxonomyMetaMutationsModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AllowedTaxonomyMetaModifyPluginSettingsFixtureEndpointWebserverRequestTest
{
    protected static function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-allowed-taxonomy-meta-mutations';
    }

    protected static function getEndpoint(): string
    {
        return 'graphql/nested-mutations/';
    }
}
