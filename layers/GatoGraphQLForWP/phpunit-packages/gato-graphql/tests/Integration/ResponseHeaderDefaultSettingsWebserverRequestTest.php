<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GatoGraphQL\GatoGraphQL\Constants\ModuleSettingOptions;
use PHPUnitForGatoGraphQL\WebserverRequests\AbstractResponseHeaderWebserverRequestTestCase;
use PHPUnitForGatoGraphQL\WebserverRequests\ModifyPluginSettingsWebserverRequestTestCaseTrait;

/**
 * Before running the test, modify the Default Settings for Cache Control Lists,
 * and validate that the results are different than those on the sibling test
 * for these given endpoints, where they have no Schema Config or CCL applied.
 *
 * @see ResponseHeaderWebserverRequestTest.php
 */
class ResponseHeaderDefaultSettingsWebserverRequestTest extends AbstractResponseHeaderWebserverRequestTestCase
{
    use ResponseHeaderWebserverRequestTestTrait;
    use ModifyPluginSettingsWebserverRequestTestCaseTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $this->modifyPluginSettingsSetUp();
    }

    protected function tearDown(): void
    {
        $this->modifyPluginSettingsTearDown();

        parent::tearDown();
    }

    protected function getSettingsKey(): string
    {
        return ModuleSettingOptions::ENTRIES;
    }

    protected function getModuleID(string $dataName): string
    {
        return 'gatographql_gatographql_response-headers';
    }

    protected function getPluginSettingsNewValue(): mixed
    {
        return [
            'Access-Control-Allow-Headers: content-type,content-length,accept',
        ];
    }

    /**
     * @return array<string,string[]>
     */
    public static function provideResponseHeaderEntries(): array
    {
        return [
            // These ones have a CCL assigned, hence compare that the value produced is the same
            'mobile-app-ccl-title-field' => [
                'graphql-query/latest-posts-for-mobile-app/',
                'max-age=30',
            ],
            'website-ccl-nofield-shorter-caching' => [
                'graphql-query/website/home-tag-widget/with-grandparent/',
                sprintf('max-age=%s', 54345),
            ],
            'custom-endpoint-2' => [
                'graphql/mobile-app/?query={ posts { id title url author { id name url } } }',
                sprintf('max-age=%s', 45),
            ],
            // The ones below originally have no CCL, hence check their value differs
            'single-endpoint-2' => [
                'graphql/?query={ posts { id title url author { id name url } } }',
                sprintf('max-age=%s', 45),
            ],
            'persisted-query-without-schema-config' => [
                'graphql-query/user-account/',
                sprintf('max-age=%s', 45),
            ],
            'custom-endpoint-without-schema-config' => [
                'graphql/back-end-for-dev/?query={ posts { id title url author { id name url } } }',
                sprintf('max-age=%s', 45),
            ],
            'custom-endpoint-with-empty-schema-config' => [
                'graphql/with-empty-schema-config/?query={ posts { id title url author { id name url } } }',
                sprintf('max-age=%s', 45),
            ],
        ];
    }
}
