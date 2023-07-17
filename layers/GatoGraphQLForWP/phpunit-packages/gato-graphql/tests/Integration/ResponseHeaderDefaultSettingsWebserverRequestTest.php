<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GatoGraphQL\GatoGraphQL\Constants\ModuleSettingOptions;
use PHPUnitForGatoGraphQL\WebserverRequests\AbstractResponseHeaderWebserverRequestTestCase;
use PHPUnitForGatoGraphQL\WebserverRequests\ModifyPluginSettingsWebserverRequestTestCaseTrait;

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
            'Access-Control-Allow-Origin: null',
            'Access-Control-Allow-Headers: content-type,content-length,accept',
        ];
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
