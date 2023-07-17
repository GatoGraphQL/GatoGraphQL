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
        $responseHeaderEntries = [
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

        /**
         * Test "no-store" on each of the Visitor IP persisted queries,
         * each of them targetting a different combination of field/directive,
         * behavior and IPs
         */
        $visitorIPPersistedQuerySlugs = [
            // 'no-acl',
            'public-on-field---deny-on-lando-ip',
            'private-on-field---deny-on-lando-ip',
            'public-on-field---allow-on-lando-ip',
            'private-on-field---allow-on-lando-ip',
            'default-on-global-field---deny-on-lando-ip',
            'default-on-global-field---allow-on-lando-ip',
            'public-on-field---deny-on-non-lando-ip',
            'private-on-field---deny-on-non-lando-ip',
            'public-on-field---allow-on-non-lando-ip',
            'private-on-field---allow-on-non-lando-ip',
            'default-on-global-field---deny-on-non-lando-ip',
            'default-on-global-field---allow-on-non-lando-ip',
            'public-on-directive---deny-on-lando-ip',
            'public-on-directive---allow-on-lando-ip',
            'private-on-directive---deny-on-lando-ip',
            'private-on-directive---allow-on-lando-ip',
            'public-on-directive---deny-on-non-lando-ip',
            'public-on-directive---allow-on-non-lando-ip',
            'private-on-directive---deny-on-non-lando-ip',
            'private-on-directive---allow-on-non-lando-ip',
        ];
        foreach ($visitorIPPersistedQuerySlugs as $visitorIPPersistedQuerySlug) {
            $entryName = sprintf('accessing-fields-with-visitor-ip-acl-rule:%s', $visitorIPPersistedQuerySlug);
            $responseHeaderEntries[$entryName] = [
                sprintf('graphql-query/accessing-field-with-visitor-ip-acl-rule/%s/', $visitorIPPersistedQuerySlug),
                'no-store',
            ];
        }

        /**
         * "Disabled access" ACL must still apply @responseHeader (i.e. instead of "no-store")
         */
        $disableAccessPersistedQuerySlugs = [
            'public-field',
            'private-field',
            'public-global-field',
            'private-global-field',
            'public-directive',
            'private-directive',
        ];
        foreach ($disableAccessPersistedQuerySlugs as $disableAccessPersistedQuerySlug) {
            $entryName = sprintf('acl-disabled-schema-elements:%s', $disableAccessPersistedQuerySlug);
            $responseHeaderEntries[$entryName] = [
                sprintf('graphql-query/acl-disabled-schema-elements/%s/', $disableAccessPersistedQuerySlug),
                sprintf('max-age=%s', ''),
            ];
        }
        return $responseHeaderEntries;
    }
}
