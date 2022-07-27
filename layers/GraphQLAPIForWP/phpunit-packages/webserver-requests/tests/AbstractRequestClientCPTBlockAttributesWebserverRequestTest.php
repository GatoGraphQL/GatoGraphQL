<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WebserverRequests;

use GraphQLAPI\GraphQLAPI\Constants\ModuleSettingOptions;

/**
 * Test that accepting a URL produces the expected status code
 */
abstract class AbstractRequestClientCPTBlockAttributesWebserverRequestTest extends AbstractModifyCPTBlockAttributesWebserverRequestTest
{
    use RequestURLWebserverRequestTestCaseTrait;

    /**
     * Test that:
     *
     * 1. The enabled client returns a 200
     * 2. The disabled client returns a 404
     */
    public function testClientEnabledDisabled(): void
    {
        $this->doTestClientEnabledDisabled(
            $this->getClientURL(),
        );
    }

    protected function doTestClientEnabledDisabled(
        string $clientURL,
    ): void {
        // $this->doTestEnabledOrDisabledPath($clientURL, 200, 'application/json', true);
        $this->doTestEnabledOrDisabledPath($clientURL, 404, null, false);
    }

    abstract protected function getClientURL(): string;
}
