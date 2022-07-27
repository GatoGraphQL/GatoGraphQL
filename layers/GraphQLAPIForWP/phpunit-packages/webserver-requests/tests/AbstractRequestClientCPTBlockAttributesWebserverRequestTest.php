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

    protected function provideClientEnabledDisabledEntries(): array
    {
        return [
            'original' => [true],
            'updated' => [false],
        ];
    }

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
}
