<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

/**
 * By default, the single endpoint has no "schema configuration"
 * assigned to it, so there is no validation to allow or deny
 * access to "private data" fields, and these are always accessible
 */
class SingleEndpointTreatBlockTypesAsPrivateDataModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractTreatBlockTypesAsPrivateDataModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    protected static function getEndpoint(): string
    {
        return 'graphql';
    }

    /**
     * "Private fields" must be accessible whether the "treat block types
     * as private" is true or false.
     *
     * Then, override the content of "block-types:0.json", which denies
     * access to the field, with the content from "block-types.json",
     * which allows it.
     *
     * @param array<string,mixed> $providerItems
     * @return array<string,mixed>
     */
    protected static function customizeProviderEndpointEntries(array $providerItems): array
    {
        $providerItems['block-types:0'][1] = $providerItems['block-types'][1];
        return $providerItems;
    }
}
