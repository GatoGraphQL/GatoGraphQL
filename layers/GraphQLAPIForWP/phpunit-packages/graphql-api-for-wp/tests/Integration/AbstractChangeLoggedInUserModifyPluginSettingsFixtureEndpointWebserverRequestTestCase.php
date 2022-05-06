<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

/**
 * Execute the operations with a user other than the "admin"
 */
abstract class AbstractChangeLoggedInUserModifyPluginSettingsFixtureEndpointWebserverRequestTestCase extends AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    /**
     * After the initial set-up, log the "admin" out,
     * and log a different user in
     */
    protected function setUp(): void
    {
        parent::setUp();

        // ...
    }

    /**
     * Log the different user out, and again the "admin" in,
     * and then continue the original set-up
     */
    protected function tearDown(): void
    {
        // ...
        
        parent::tearDown();
    }
}
