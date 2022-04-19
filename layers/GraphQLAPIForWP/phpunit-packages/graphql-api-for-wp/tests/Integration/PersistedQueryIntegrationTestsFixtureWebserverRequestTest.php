<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use PHPUnitForGraphQLAPI\WebserverRequests\AbstractFixtureWebserverRequestTestCase;

class PersistedQueryIntegrationTestsFixtureWebserverRequestTest extends AbstractFixtureWebserverRequestTestCase
{
    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture';
    }

    protected function getEndpoint(string $dataName): string
    {
        return match ($dataName) {
            'persisted-query',
            'persisted-query-by-post',
            'persisted-query-passing-params'
                => 'graphql-query/latest-posts-for-mobile-app/',
            'persisted-query-with-api-hierarchy'
                => 'graphql-query/website/home-posts-widget/',
            'persisted-query-with-disabled-params'
                => 'graphql-query/website/home-post-widget/',
            default => parent::getEndpoint($dataName),
        };
    }

    protected function getEntryMethod(string $dataName): string
    {
        return match ($dataName) {
            'persisted-query-by-post' => 'POST',
            default => 'GET',
        };
    }

    /**
     * @return array<string,mixed>
     */
    protected function getParams(string $dataName): array
    {
        return match ($dataName) {
            'persisted-query-passing-params',
            'persisted-query-with-disabled-params'
                => [
                    'limit' => 3,
                ],
            default
                => parent::getParams($dataName),
        };
    }
}
