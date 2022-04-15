<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\WebserverRequests;

use PHPUnitForGraphQLAPI\WebserverRequests\AbstractFixtureWebserverRequestTestCase;

class LocalhostFixtureWebserverRequestTest extends AbstractFixtureWebserverRequestTestCase
{
    use LocalhostWebserverRequestTestTrait;

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
                => 'graphql-query/latest-posts-for-mobile-app-2',
            default => parent::getEndpoint($dataName),
        };
    }

    protected function getEntryMethod(string $dataName): string
    {
        return match ($dataName) {
            'persisted-query',
            'persisted-query-passing-params'
                => 'GET',
            default
                => parent::getEntryMethod($dataName),
        };
    }

    /**
     * @return array<string,mixed>
     */
    protected function getParams(string $dataName): array
    {
        return match ($dataName) {
            'persisted-query-passing-params' => [
                'limit' => 2,
            ],
            default => parent::getParams($dataName),
        };
    }
}
