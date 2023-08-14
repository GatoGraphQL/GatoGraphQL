<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use PHPUnitForGatoGraphQL\WebserverRequests\AbstractPersistedQueryFixtureWebserverRequestTestCase;

class PersistedQueryFixtureWebserverRequestTest extends AbstractPersistedQueryFixtureWebserverRequestTestCase
{
    protected static function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-persisted-queries';
    }

    protected static function getEndpoint(string $dataName): string
    {
        return match ($dataName) {
            'basic',
            'by-post',
            'passing-params',
            'by-post-passing-params-via-body'
                => 'graphql-query/latest-posts-for-mobile-app/',
            'do-not-override-params-with-none-set'
                => 'graphql-query/website/home-tag-widget/',
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

    protected static function getEntryMethod(string $dataName): string
    {
        return match ($dataName) {
            'by-post',
            'by-post-passing-params-via-body'
                => 'POST',
            default
                => 'GET',
        };
    }

    /**
     * @return array<string,mixed>
     */
    protected static function getParams(string $dataName): array
    {
        return match ($dataName) {
            'passing-params',
            'do-not-override-params-with-none-set',
            'do-not-override-params-with-some-set'
                => [
                    'limit' => 3,
                ],
            default
                => parent::getParams($dataName),
        };
    }

    /**
     * @return array<string,mixed>
     */
    protected static function getVariables(string $dataName): array
    {
        return match ($dataName) {
            'by-post-passing-params-via-body' => [
                'limit' => 3,
            ],
            default => parent::getVariables($dataName),
        };
    }
}
