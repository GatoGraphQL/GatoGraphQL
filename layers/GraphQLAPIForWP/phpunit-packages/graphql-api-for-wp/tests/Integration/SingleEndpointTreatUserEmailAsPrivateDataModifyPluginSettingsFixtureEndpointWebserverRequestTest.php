<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

/**
 * By default, the single endpoint has no "schema configuration"
 * assigned to it, so there is no validation to allow or deny
 * access to "private data" fields, and these are always accessible
 */
class SingleEndpointTreatUserEmailAsPrivateDataModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractTreatUserEmailAsPrivateDataModifyPluginSettingsFixtureEndpointWebserverRequestTest
{
    protected function getEndpoint(): string
    {
        return 'graphql/';
    }

    /**
     * "Private fields" must be accessible whether the "treat user email
     * as private" is true or false.
     * 
     * Then, override the content of "user-email:0.json", which denies
     * access to the field, with the content from "user-email.json",
     * which allows it.
     *
     * @param array<string,mixed> $providerItems
     * @return array<string,mixed>
     */
    protected function customizeProviderEndpointEntries(array $providerItems): array
    {
        $providerItems['user-email:0'] = $providerItems['user-email'];
        return $providerItems;
    }
}
