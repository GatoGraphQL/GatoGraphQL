<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WebserverRequests;

use GraphQLAPI\GraphQLAPI\Constants\ModuleSettingOptions;
use GraphQLByPoP\GraphQLClientsForWP\Constants\CustomHeaders;

/**
 * Test that accepting a URL produces the expected status code
 */
abstract class AbstractRequestClientCPTBlockAttributesWebserverRequestTest extends AbstractModifyCPTBlockAttributesWebserverRequestTest
{
    use RequestURLWebserverRequestTestCaseTrait;

    protected function executeCPTBlockAttributesSetUpTearDown(string $dataName): bool
    {
        if ($dataName === 'original') {
            return false;
        }
        return true;
    }

    /**
     * Test that:
     *
     * 1. The enabled client returns a 200
     * 2. The disabled client returns a 404
     * 
     * @dataProvider provideClientEnabledDisabledEntries
     */
    public function testClientEnabledDisabled(bool $enabled): void
    {
        $this->doTestClientEnabledDisabled(
            $this->getClientURL(),
            $enabled,
        );
    }

    /**
     * Provide always two conditions to test:
     *
     * - Before update: custom header is present (or not)
     * - After update: custom header is not present (or is)
     *
     * @return array<string,array<mixed>>
     */
    final protected function provideClientEnabledDisabledEntries(): array
    {
        $isUpdatedClientEnabled = $this->isUpdatedClientEnabled();
        return [
            'original' => [!$isUpdatedClientEnabled],
            'updated' => [$isUpdatedClientEnabled],
        ];
    }

    /**
     * The client will always return a 200 status, whether
     * enabled or disabled. The difference is the custom header.
     */
    protected function doTestClientEnabledDisabled(
        string $clientURL,
        bool $enabled,
    ): void {
        $this->doTestEnabledOrDisabledPath($clientURL, 200, null, $enabled);
    }

    protected function getCustomHeader(): ?string
    {
        return CustomHeaders::CLIENT_ENDPOINT;
    }

    abstract protected function getClientURL(): string;
    
    abstract protected function isUpdatedClientEnabled(): bool;
}
