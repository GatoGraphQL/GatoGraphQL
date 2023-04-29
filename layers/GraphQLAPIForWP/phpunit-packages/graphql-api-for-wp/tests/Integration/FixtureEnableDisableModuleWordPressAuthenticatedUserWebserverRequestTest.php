<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use PHPUnitForGraphQLAPI\WebserverRequests\AbstractFixtureEnableDisableModuleWordPressAuthenticatedUserWebserverRequestTest;

/**
 * Test that enabling/disabling a module works well.
 */
class FixtureEnableDisableModuleWordPressAuthenticatedUserWebserverRequestTest extends AbstractFixtureEnableDisableModuleWordPressAuthenticatedUserWebserverRequestTest
{
    /**
     * Directory under the fixture files are placed
     */
    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-enable-disable-modules';
    }

    protected function getModuleEndpoint(string $fileName): ?string
    {
        return match ($fileName) {
            'schema-configuration' => 'graphql/customers/penguin-books/',
            default => parent::getModuleEndpoint($fileName),
        };
    }
}
