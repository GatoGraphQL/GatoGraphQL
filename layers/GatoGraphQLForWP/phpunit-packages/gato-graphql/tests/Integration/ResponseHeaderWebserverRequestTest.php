<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use PHPUnitForGatoGraphQL\WebserverRequests\AbstractResponseHeaderWebserverRequestTestCase;

class ResponseHeaderWebserverRequestTest extends AbstractResponseHeaderWebserverRequestTestCase
{
    use ResponseHeaderWebserverRequestTestTrait;

    /**
     * @return array<string,string[]>
     */
    public static function provideResponseHeaderEntries(): array
    {
        return [
            'mobile-app-ccl-title-field' => [
                'graphql-query/latest-posts-for-mobile-app/',
                'max-age=30',
            ],
            'website-ccl-nofield' => [
                'graphql-query/website/home-tag-widget/',
                sprintf('max-age=%s', ''),
            ],
            'website-ccl-nofield-shorter-caching' => [
                'graphql-query/website/home-tag-widget/with-grandparent/',
                sprintf('max-age=%s', 54345),
            ],
            'website-ccl-inherit-schemaconfig-from-parent-displayName-field' => [
                'website/home-user-widget/',
                'max-age=20',
            ],
            'website-ccl-inherit-schemaconfig-from-parent-username-field' => [
                'website/home-posts-widget/',
                'max-age=12000',
            ],
            'persisted-query-without-schema-config' => [
                'graphql-query/user-account/',
                sprintf('max-age=%s', ''),
            ],
            'user-must-be-logged-in' => [
                'graphql-query/logged-in-user-account/',
                'no-store',
            ],
            'single-endpoint' => [
                'graphql/?query={ id }',
                sprintf('max-age=%s', 315360000),
            ],
            'single-endpoint-2' => [
                'graphql/?query={ posts { id title url author { id name url } } }',
                sprintf('max-age=%s', ''),
            ],
            'mutation' => [
                'graphql/?query=mutation { updatePost(input: { id: 88888, title: "doesn\'t matter" } ) { id } }',
                'no-store',
            ],
            'custom-endpoint' => [
                'graphql/mobile-app/?query={ id }',
                sprintf('max-age=%s', 315360000),
            ],
            'custom-endpoint-2' => [
                'graphql/mobile-app/?query={ posts { id title url author { id name url } } }',
                sprintf('max-age=%s', 45),
            ],
            'custom-endpoint-without-schema-config' => [
                'graphql/back-end-for-dev/?query={ posts { id title url author { id name url } } }',
                sprintf('max-age=%s', ''),
            ],
            'custom-endpoint-with-empty-schema-config' => [
                'graphql/with-empty-schema-config/?query={ posts { id title url author { id name url } } }',
                sprintf('max-age=%s', ''),
            ],
            'accessing-fields-with-visitor-ip-acl-rule' => [
                'graphql-query/accessing-field-with-visitor-ip-acl-rule/',
                'no-store',
            ],
            'accessing-field-with-visitor-ip-acl-rule:no-acl' => [
                'graphql-query/accessing-field-with-visitor-ip-acl-rule/no-acl/',
                sprintf('max-age=%s', ''),
            ],
        ];
    }
}
