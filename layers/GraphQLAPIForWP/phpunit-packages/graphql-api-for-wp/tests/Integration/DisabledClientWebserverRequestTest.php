<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use PHPUnitForGraphQLAPI\WebserverRequests\AbstractDisabledClientWebserverRequestTestCase;
use PHPUnitForGraphQLAPI\WebserverRequests\EnableDisableModuleWebserverRequestTestTrait;
use PHPUnitForGraphQLAPI\WebserverRequests\RequestRESTAPIWordPressAuthenticatedUserWebserverRequestTestTrait;

/**
 * Test that disabling clients (GraphiQL/Voyager) works well
 */
class DisabledClientWebserverRequestTest extends AbstractDisabledClientWebserverRequestTestCase
{
    use RequestRESTAPIWordPressAuthenticatedUserWebserverRequestTestTrait;
    use SingleEndpointClientWebserverRequestTestCaseTrait;
    use EnableDisableModuleWebserverRequestTestTrait;

    protected function setUp(): void
    {
        parent::setUp();

        /**
         * To test their disabled state works well, first execute
         * a REST API call to disable the client, and then re-enable
         * it afterwards.
         */
        $dataName = $this->getDataName();
        if (str_starts_with($dataName, 'single-endpoint-')) {
            $this->executeRESTEndpointToEnableOrDisableModule($dataName, false);
        }
    }

    protected function tearDown(): void
    {
        /**
         * Re-enable the clients for the single endpoint
         * after the "disabled" test
         */
        $dataName = $this->getDataName();
        if (str_starts_with($dataName, 'single-endpoint-')) {
            $this->executeRESTEndpointToEnableOrDisableModule($dataName, true);
        }

        parent::tearDown();
    }

    /**
     * @return array<string,mixed[]>
     */
    protected function provideDisabledClientEntries(): array
    {
        return [
            'single-endpoint-graphiql' => [
                'graphiql/',
                404,
            ],
            'single-endpoint-voyager' => [
                'schema/',
                404,
            ],
            'custom-endpoint-graphiql' => [
                'graphql/customers/penguin-books/?view=graphiql',
                200,
            ],
            'custom-endpoint-voyager' => [
                'graphql/customers/penguin-books/?view=schema',
                200,
            ],
        ];
    }

    protected function getModuleID(string $dataName): string
    {
        return $this->getSingleEndpointClientModuleID($dataName);
    }
}
