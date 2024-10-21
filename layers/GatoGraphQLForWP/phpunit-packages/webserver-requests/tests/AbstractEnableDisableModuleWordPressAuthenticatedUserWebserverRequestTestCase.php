<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\WebserverRequests;

/**
 * Test that enabling/disabling a module works well.
 *
 * It uses the REST API to disable/enable the module before/after executing
 * the test. That's why these tests are done with the authenticated user
 * in WordPress, so the user can execute operations via the REST endpoint.
 */
abstract class AbstractEnableDisableModuleWordPressAuthenticatedUserWebserverRequestTestCase extends AbstractEndpointWebserverRequestTestCase
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
    public static function provideEndpointEntries(): array
    {
        $endpoint = static::getEndpoint();
        $providerEntries = [];
        foreach (static::getModuleNameEntries() as $moduleName => $moduleEntry) {
            $providerEntries[$moduleName . ':enabled'] = [
                static::getExpectedContentType(true),
                $moduleEntry['response-enabled'],
                $moduleEntry['endpoint'] ?? $endpoint,
                [],
                $moduleEntry['query'],
            ];
            $providerEntries[$moduleName . ':disabled'] = [
                static::getExpectedContentType(false),
                $moduleEntry['response-disabled'],
                $moduleEntry['endpoint'] ?? $endpoint,
                [],
                $moduleEntry['query'],
            ];
        }
        return static::customizeProviderEndpointEntries($providerEntries);
    }

    /**
     * @param array<string,mixed> $providerItems
     * @return array<string,mixed>
     */
    protected static function customizeProviderEndpointEntries(array $providerItems): array
    {
        return $providerItems;
    }

    protected static function getExpectedContentType(bool $enabled): string
    {
        return 'application/json';
    }

    protected static function getEndpoint(): string
    {
        if (static::useAdminEndpoint()) {
            return static::getAdminEndpoint();
        }
        return 'graphql/';
    }

    protected static function useAdminEndpoint(): bool
    {
        return false;
    }

    protected static function getAdminEndpoint(): string
    {
        return 'wp-admin/edit.php?page=gatographql&action=execute_query';
    }

    /**
     * @return array<string,array<string,mixed>> An array of [$moduleName => ['query' => "...", 'response-enabled' => "...", 'response-disabled' => "..."], 'endpoint' => "..."]
     */
    abstract protected static function getModuleNameEntries(): array;
}
