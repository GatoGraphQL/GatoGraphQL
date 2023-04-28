<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use Exception;
use PHPUnitForGraphQLAPI\WebserverRequests\EnableDisableModuleWebserverRequestTestTrait;
use PHPUnitForGraphQLAPI\WebserverRequests\RequestRESTAPIWordPressAuthenticatedUserWebserverRequestTestTrait;

abstract class AbstractDisableSchemaModulesOnPrivateEndpointsFixtureEndpointWebserverRequestTest extends AbstractFixtureEndpointWebserverRequestTestCase
{
    use EnableDisableModuleWebserverRequestTestTrait;
    use RequestRESTAPIWordPressAuthenticatedUserWebserverRequestTestTrait;

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

    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-disable-schema-modules-on-private-endpoints';
    }

    protected function getModuleID(string $dataName): string
    {
        /**
         * This method will be called to enable/disable the module
         */
        if ($dataName === self::ARTIFICIAL_DATA_NAME) {
            return 'graphqlapi_graphqlapi_schema-users';
        }
        throw new Exception(sprintf('Unexpected dataName %s', $dataName));
    }
}
