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
            'passing-params'
                => 'graphql-query/latest-posts-for-mobile-app/',
            'with-api-hierarchy'
                => 'graphql-query/website/home-posts-widget/',
            'with-disabled-params'
                => 'graphql-query/website/home-post-widget/',
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
            'passing-params',
            'with-disabled-params'
                => [
                    'limit' => 3,
                ],
            default
                => parent::getParams($dataName),
        };
    }
}
