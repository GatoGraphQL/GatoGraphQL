<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WebserverRequests;

use GraphQLAPI\GraphQLAPI\Constants\BlockAttributeNames;
use GraphQLByPoP\GraphQLClientsForWP\Constants\CustomHeaders;

/**
 * Test that accepting a URL produces the expected status code
 */
abstract class AbstractRequestClientCPTBlockAttributesWebserverRequestTest extends AbstractModifyCPTBlockAttributesWebserverRequestTest
{
    use RequestURLWebserverRequestTestCaseTrait;

    protected const ORIGINAL_DATA_NAME = 'original';
    protected const UPDATED_DATA_NAME = 'updated';

    /**
     * Depending on the condition, either execute the
     * REST endpoint to update the CPT block attribute
     * or not.
     */
    protected function executeCPTBlockAttributesSetUpTearDown(string $dataName): bool
    {
        if ($dataName === self::ORIGINAL_DATA_NAME) {
            return false;
        }
        if ($dataName === self::UPDATED_DATA_NAME) {
            return true;
        }
        return parent::executeCPTBlockAttributesSetUpTearDown($dataName);
    }

    /**
     * The client will always return a 200 status, whether
     * enabled or disabled. The difference is the custom header.
     *
     * @dataProvider provideClientEnabledDisabledEntries
     */
    public function testClientEnabledDisabled(bool $enabled): void
    {
        $clientURL = $this->getClientURL();
        $this->doTestEnabledOrDisabledPath($clientURL, 200, null, $enabled);
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
            self::ORIGINAL_DATA_NAME => [!$isUpdatedClientEnabled],
            self::UPDATED_DATA_NAME => [$isUpdatedClientEnabled],
        ];
    }

    protected function getCustomHeader(): ?string
    {
        return CustomHeaders::CLIENT_ENDPOINT;
    }

    abstract protected function getClientURL(): string;

    protected function getCPTBlockAttributesNewValue(): array
    {
        return [
            BlockAttributeNames::IS_ENABLED => $this->isUpdatedClientEnabled(),
        ];
    }

    /**
     * The default state is for the clients to be enabled.
     * Then, when updated, they will be disabled.
     */
    protected function isUpdatedClientEnabled(): bool
    {
        return false;
    }
}
