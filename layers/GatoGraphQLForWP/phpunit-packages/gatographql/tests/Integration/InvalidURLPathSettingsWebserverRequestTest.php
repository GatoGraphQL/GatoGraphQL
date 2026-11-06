<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use PHPUnitForGatoGraphQL\WebserverRequests\AbstractInvalidURLPathSettingsWebserverRequestTestCase;

use function str_repeat;
use function str_starts_with;

/**
 * Test that invalid paths for the single endpoint and
 * its clients (GraphiQL/Voyager) are not stored
 */
class InvalidURLPathSettingsWebserverRequestTest extends AbstractInvalidURLPathSettingsWebserverRequestTestCase
{
    /**
     * @return array<string,string[]> Array of 2 elements: [ ${moduleID}, ${invalidPath} ]
     */
    public static function provideInvalidPathEntries(): array
    {
        $singleEndpointModuleID = 'gatographql_gatographql_single-endpoint';
        $graphiQLModuleID = 'gatographql_gatographql_graphiql-for-single-endpoint';
        $voyagerModuleID = 'gatographql_gatographql_interactive-schema-for-single-endpoint';
        return [
            'single-endpoint-unsupported-chars' => [
                $singleEndpointModuleID,
                'gr@ph!ql',
            ],
            'single-endpoint-regex-chars' => [
                $singleEndpointModuleID,
                '.*',
            ],
            'single-endpoint-non-ascii-chars' => [
                $singleEndpointModuleID,
                'café',
            ],
            'single-endpoint-empty' => [
                $singleEndpointModuleID,
                '/',
            ],
            'single-endpoint-double-slash' => [
                $singleEndpointModuleID,
                'api//graphql',
            ],
            'single-endpoint-too-long' => [
                $singleEndpointModuleID,
                str_repeat('a', 201),
            ],
            'single-endpoint-rest-api' => [
                $singleEndpointModuleID,
                'wp-json/graphql',
            ],
            'single-endpoint-wordpress-folder' => [
                $singleEndpointModuleID,
                'wp-admin',
            ],
            'single-endpoint-feed' => [
                $singleEndpointModuleID,
                'feed',
            ],
            'single-endpoint-same-as-graphiql' => [
                $singleEndpointModuleID,
                'graphiql',
            ],
            'graphiql-same-as-single-endpoint' => [
                $graphiQLModuleID,
                '/graphql/',
            ],
            'voyager-unsupported-chars' => [
                $voyagerModuleID,
                'sch ema',
            ],
        ];
    }

    protected function doTestPreviousPathIsAccessible(string $previousPath): void
    {
        $isClient = !str_starts_with((string) $this->dataName(), 'single-endpoint-');
        $this->doTestEnabledOrDisabledPath(
            $previousPath,
            200,
            $isClient ? 'text/html' : 'application/json',
            true
        );
    }
}
