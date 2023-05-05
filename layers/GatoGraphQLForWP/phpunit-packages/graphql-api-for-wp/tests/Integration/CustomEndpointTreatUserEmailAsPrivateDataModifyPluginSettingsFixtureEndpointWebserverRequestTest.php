<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

/**
 * Use a custom endpoint that has option "Expose sensitive data in the schema"
 * with value "Do not expose sensitive data".
 *
 * Then, those fields treated as “sensitive” won't be added to the schema,
 * and the query will produce an error.
 */
class CustomEndpointTreatUserEmailAsPrivateDataModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractTreatUserEmailAsPrivateDataModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    protected function getEndpoint(): string
    {
        return 'graphql/website/';
    }
}
