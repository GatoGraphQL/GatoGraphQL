<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use PHPUnitForGraphQLAPI\WebserverRequests\EnableDisableModuleWebserverRequestTestTrait;

class SchemaConfigurationForSingleEndpointWithModuleDisabledQueryExecutionModifyPluginSettingsFixtureEndpointWebserverRequestTest extends SchemaConfigurationForSingleEndpointQueryExecutionModifyPluginSettingsFixtureEndpointWebserverRequestTest
{
    use EnableDisableModuleWebserverRequestTestTrait;

    private const ARTIFICIAL_DATA_NAME = 'artificial';

    protected function setUp(): void
    {
        parent::setUp();

        /**
         * Disable the "Users" module before executing the test
         */
        $this->executeRESTEndpointToEnableOrDisableModule(self::ARTIFICIAL_DATA_NAME, false);
    }

    protected function tearDown(): void
    {
        /**
         * Re-enable the "Users" module after executing the test
         */
        $this->executeRESTEndpointToEnableOrDisableModule(self::ARTIFICIAL_DATA_NAME, true);

        parent::tearDown();
    }

    protected function getModuleID(string $dataName): string
    {
        /**
         * This method will be called from 2 different directions:
         *
         * - To enable/disable the module
         * - To apply the Settings change in the DB
         *
         * Use an "artificial" $dataName to distinguish between them.
         */
        if ($dataName === self::ARTIFICIAL_DATA_NAME) {
            return 'graphqlapi_graphqlapi_schema-configuration';
        }

        return parent::getModuleID($dataName);
    }

    protected function getResponseFixtureFolder(): string
    {
        return __DIR__ . '/fixture-schema-configuration-for-single-endpoint-with-module-disabled';
    }

    /**
     * Disabling the Schema Configuration module implies that there
     * will be no changes before/after executing the test.
     *
     * Then, have the content of the 2 .json files be always the same
     *
     * @param array<string,mixed> $providerItems
     * @return array<string,mixed>
     */
    protected function customizeProviderEndpointEntries(array $providerItems): array
    {
        $providerItems['admin-fields'][1] = $providerItems['admin-fields:0'][1];
        return $providerItems;
    }
}
