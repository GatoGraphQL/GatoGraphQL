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
            'api-hierarchy'
                => 'graphql-query/website/home-posts-widget/',
            'do-not-override-params'
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
            'params',
            'do-not-override-params'
                => [
                    'limit' => 3,
                ],
            default
                => parent::getParams($dataName),
        };
    }
}
