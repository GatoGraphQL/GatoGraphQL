<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use PHPUnitForGraphQLAPI\WebserverRequests\AbstractFixtureWebserverRequestTestCase;

class PersistedQueryIntegrationTestsFixtureWebserverRequestTest extends AbstractFixtureWebserverRequestTestCase
{
    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-persisted-queries';
    }

    protected function getEndpoint(string $dataName): string
    {
        return match ($dataName) {
            'basic',
            'by-post',
            'params'
                => 'graphql-query/latest-posts-for-mobile-app/',
            'do-not-override-params-with-none-set'
                => 'graphql-query/website/home-post-widget/',
            'do-not-override-params-with-some-set'
                => 'graphql-query/website/home-user-widget/',
            'api-hierarchy-child-no-inherited-query'
                => 'graphql-query/website/home-posts-widget/',
            'api-hierarchy-parent-inherited-query'
                => 'graphql-query/user-account/',
            'api-hierarchy-child-inheriting-query'
                => 'graphql-query/user-account/full-data/',
            default => parent::getEndpoint($dataName),
        };
    }

    protected function getEntryMethod(string $dataName): string
    {
        return match ($dataName) {
            'by-post' => 'POST',
            default => 'GET',
        };
    }

    /**
     * @return array<string,mixed>
     */
    protected function getParams(string $dataName): array
    {
        return match ($dataName) {
            'params',
            'do-not-override-params-with-none-set',
            'do-not-override-params-with-some-set'
                => [
                    'limit' => 3,
                ],
            default
                => parent::getParams($dataName),
        };
    }
}
