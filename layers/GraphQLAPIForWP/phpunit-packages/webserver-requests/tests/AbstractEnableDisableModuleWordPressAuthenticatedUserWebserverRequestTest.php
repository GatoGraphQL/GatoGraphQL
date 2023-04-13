<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WebserverRequests;

/**
 * Test that enabling/disabling a module works well.
 *
 * It uses the REST API to disable/enable the module before/after executing
 * the test. That's why these tests are done with the authenticated user
 * in WordPress, so the user can execute operations via the REST endpoint.
 */
abstract class AbstractEnableDisableModuleWordPressAuthenticatedUserWebserverRequestTest extends AbstractEndpointWebserverRequestTestCase
{
    use RequestRESTAPIWordPressAuthenticatedUserWebserverRequestTestTrait;
    use EnableDisableModuleWebserverRequestTestTrait;

    protected function setUp(): void
    {
        parent::setUp();

        /**
         * Disable the module before executing the ":disabled" test
         */
        $dataName = $this->getDataName();
        if (str_ends_with($dataName, ':disabled')) {
            $this->executeRESTEndpointToEnableOrDisableModule($dataName, false);
        }
    }

    protected function tearDown(): void
    {
        /**
         * Re-enable the module after executing the ":disabled" test
         */
        $dataName = $this->getDataName();
        if (str_ends_with($dataName, ':disabled')) {
            $this->executeRESTEndpointToEnableOrDisableModule($dataName, true);
        }

        parent::tearDown();
    }

    /**
     * @return array<string,array<mixed>>
     */
    protected function provideEndpointEntries(): array
    {
        $endpoint = $this->getEndpoint();
        $providerEntries = [];
        foreach ($this->getModuleNameEntries() as $moduleName => $moduleEntry) {
            $providerEntries[$moduleName . ':enabled'] = [
                'application/json',
                $moduleEntry['response-enabled'],
                $moduleEntry['endpoint'] ?? $endpoint,
                [],
                $moduleEntry['query'],
            ];
            $providerEntries[$moduleName . ':disabled'] = [
                'application/json',
                $moduleEntry['response-disabled'],
                $moduleEntry['endpoint'] ?? $endpoint,
                [],
                $moduleEntry['query'],
            ];
        }
        return $providerEntries;
    }

    protected function getEndpoint(): ?string
    {
        return 'graphql/';
    }

    /**
     * @return array<string,array<string,mixed>> An array of [$moduleName => ['query' => "...", 'response-enabled' => "...", 'response-disabled' => "..."], 'endpoint' => "..."]
     */
    abstract protected function getModuleNameEntries(): array;
}
