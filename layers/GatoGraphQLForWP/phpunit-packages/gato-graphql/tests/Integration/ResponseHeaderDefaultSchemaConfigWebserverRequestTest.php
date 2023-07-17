<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GatoGraphQL\GatoGraphQL\Constants\ModuleSettingOptions;
use PHPUnitForGatoGraphQL\WebserverRequests\AbstractResponseHeaderWebserverRequestTestCase;
use PHPUnitForGatoGraphQL\WebserverRequests\ModifyPluginSettingsWebserverRequestTestCaseTrait;

/**
 * Before running the test, modify the Default Schema Configuration for endpoints,
 * and validate that the results are different than those on the sibling test
 * for these given endpoints, where they have no Schema Config applied.
 *
 * @see ResponseHeaderWebserverRequestTest.php
 */
class ResponseHeaderDefaultSchemaConfigWebserverRequestTest extends AbstractResponseHeaderWebserverRequestTestCase
{
    use ResponseHeaderWebserverRequestTestTrait;
    use ModifyPluginSettingsWebserverRequestTestCaseTrait;

    public const MOBILE_APP_SCHEMA_CONFIGURATION_ID = 193;

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
        return ModuleSettingOptions::SCHEMA_CONFIGURATION;
    }

    protected function getModuleID(string $dataName): string
    {
        return match ($dataName) {
            'single-endpoint-2'
                => 'gatographql_gatographql_single-endpoint',
            'persisted-query-without-schema-config'
                => 'gatographql_gatographql_persisted-queries',
            'custom-endpoint-without-schema-config',
            'custom-endpoint-with-empty-schema-config',
            'website-ccl-nofield-shorter-caching'
                => 'gatographql_gatographql_custom-endpoints',
            default
                => '',
        };
    }

    protected function getPluginSettingsNewValue(): mixed
    {
        return self::MOBILE_APP_SCHEMA_CONFIGURATION_ID;
    }

    /**
     * @return array<string,string[]>
     */
    public static function provideResponseHeaderEntries(): array
    {
        return [
            // These ones have a Schema Config assigned, hence compare that the value produced is the same
            'custom-endpoint-with-empty-schema-config' => [
                'graphql/with-empty-schema-config/?query={ posts { id title url author { id name url } } }',
                sprintf('max-age=%s', ''),
            ],
            'website-ccl-nofield-shorter-caching' => [
                'graphql-query/website/home-tag-widget/with-grandparent/',
                sprintf('max-age=%s', 54345),
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
        ];
    }
}
