<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use GraphQLAPI\GraphQLAPI\Constants\SettingsDefaultValues;
use PHPUnitForGraphQLAPI\WebserverRequests\AbstractCacheControlWebserverRequestTestCase;

class CacheControlListsWebserverRequestTest extends AbstractCacheControlWebserverRequestTestCase
{
    /**
     * @return array<string,string[]>
     */
    protected function provideCacheControlEntries(): array
    {
        return [
            'mobile-app-ccl-title-field' => [
                'graphql-query/latest-posts-for-mobile-app/',
                'max-age=30',
            ],
            'website-ccl-nofield' => [
                'website/home-tag-widget/',
                sprintf('max-age=%s', SettingsDefaultValues::CACHE_CONTROL_MAX_AGE),
            ],
            'website-ccl-inherit-schemaconfig-from-parent-displayName-field' => [
                'website/home-user-widget/',
                'max-age=20',
            ],
            'website-ccl-inherit-schemaconfig-from-parent-username-field' => [
                'website/home-posts-widget/',
                'max-age=12000',
            ],
            'user-must-be-logged-in' => [
                'graphql-query/logged-in-user-account/',
                'no-store',
            ],
        ];
    }
}
