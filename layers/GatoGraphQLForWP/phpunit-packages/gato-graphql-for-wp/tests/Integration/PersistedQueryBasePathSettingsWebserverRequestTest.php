<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

/**
 * Test that updating the base path for the persisted queries works well
 */
class PersistedQueryBasePathSettingsWebserverRequestTest extends AbstractPersistedQueryBasePathSettingsWebserverRequestTestCase
{
    protected function getModuleID(string $dataName): string
    {
        return 'gatographql_gatographql_persisted-queries';
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
