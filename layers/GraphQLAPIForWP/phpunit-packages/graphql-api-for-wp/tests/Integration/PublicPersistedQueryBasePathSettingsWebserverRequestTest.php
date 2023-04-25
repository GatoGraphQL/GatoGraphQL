<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

/**
 * Test that updating the base path for the persisted queries works well
 */
class PublicPersistedQueryBasePathSettingsWebserverRequestTest extends AbstractPersistedQueryBasePathSettingsWebserverRequestTest
{
    protected function getModuleID(string $dataName): string
    {
        return 'graphqlapi_graphqlapi_persisted-queries';
    }

    protected function getPersistedQueryURL(string $basePath): string
    {
        return $basePath . '/latest-posts-for-mobile-app/';
    }

    protected function getNonExistingPathEntry(): string
    {
        return 'graaaaaaaphql-queeeeery';
    }
}
