<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use PHPUnitForGatoGraphQL\WebserverRequests\Environment as WebserverRequestsEnvironment;

/**
 * Use a custom endpoint that has option "Expose sensitive data in the schema"
 * with value "Do not expose sensitive data".
 *
 * Then, those fields treated as “sensitive” won't be added to the schema,
 * and the query will produce an error.
 */
class CustomEndpointTreatCustomPostEditURLAsPrivateDataModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractTreatCustomPostEditURLAsPrivateDataModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    protected static function getEndpoint(): string
    {
        return 'graphql/website/';
    }

    protected function adaptResponseBody(string $responseBody): string
    {
        return str_replace(
            WebserverRequestsEnvironment::getIntegrationTestsWebserverDomain(),
            'gato-graphql.lndo.site',
            parent::adaptResponseBody($responseBody)
        );
    }
}
