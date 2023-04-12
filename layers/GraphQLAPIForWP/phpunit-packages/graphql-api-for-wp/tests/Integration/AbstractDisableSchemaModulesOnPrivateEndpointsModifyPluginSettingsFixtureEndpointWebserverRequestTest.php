<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\PluginGeneralSettingsFunctionalityModuleResolver;
use PHPUnitForGraphQLAPI\WebserverRequests\EnableDisableModuleWebserverRequestTestTrait;

abstract class AbstractDisableSchemaModulesOnPrivateEndpointsModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    use EnableDisableModuleWebserverRequestTestTrait;

    private const ARTIFICIAL_DATA_NAME = 'artificial';
    
    protected function setUp(): void
    {
        parent::setUp();

        /**
         * Disable the "User Roles" module before executing the test
         */
        $this->executeRESTEndpointToEnableOrDisableModule(self::ARTIFICIAL_DATA_NAME, false);
    }

    protected function tearDown(): void
    {
        /**
         * Re-enable the "User Roles" module after executing the test
         */
        $this->executeRESTEndpointToEnableOrDisableModule(self::ARTIFICIAL_DATA_NAME, true);

        parent::tearDown();
    }

    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-disable-schema-modules-on-private-endpoints';
    }

    protected function getSettingsKey(): string
    {
        return PluginGeneralSettingsFunctionalityModuleResolver::OPTION_DISABLE_SCHEMA_MODULES_IN_PRIVATE_ENDPOINTS;
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
            return 'graphqlapi_graphqlapi_schema-user-roles';
        }

        return 'graphqlapi_graphqlapi_private-endpoints';
    }

    protected function getPluginSettingsNewValue(): mixed
    {
        return true;
    }
}
