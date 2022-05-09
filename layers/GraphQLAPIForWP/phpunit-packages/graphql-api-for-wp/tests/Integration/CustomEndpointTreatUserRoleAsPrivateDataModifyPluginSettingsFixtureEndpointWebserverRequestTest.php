<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

/**
 * Use a custom endpoint that has option "Expose admin elements in the schema?"
 * with value "Do not expose admin elements".
 *
 * Then, those fields treated as "admin" won't be added to the schema,
 * and the query will produce an error.
 */
class CustomEndpointTreatUserRoleAsPrivateDataModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractTreatUserRoleAsPrivateDataModifyPluginSettingsFixtureEndpointWebserverRequestTest
{
    protected function getEndpoint(): string
    {
        return 'graphql/power-users/';
    }
}
