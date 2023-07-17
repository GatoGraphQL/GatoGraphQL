<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GatoGraphQL\GatoGraphQL\Constants\ModuleSettingOptions;
use PHPUnitForGatoGraphQL\WebserverRequests\AbstractResponseHeaderWebserverRequestTestCase;
use PHPUnitForGatoGraphQL\WebserverRequests\ModifyPluginSettingsWebserverRequestTestCaseTrait;

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
            'single-endpoint' => [
                'graphql/?query={ id }',
                'content-type,content-length,accept',
            ],
        ];
    }
}
