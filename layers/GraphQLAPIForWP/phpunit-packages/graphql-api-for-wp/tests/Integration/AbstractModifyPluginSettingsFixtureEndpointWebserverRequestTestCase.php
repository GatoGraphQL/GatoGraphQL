<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use PHPUnitForGraphQLAPI\WebserverRequests\ModifyPluginSettingsWebserverRequestTestCaseTrait;

abstract class AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCase extends AbstractFixtureEndpointWebserverRequestTestCase
{
    use ModifyPluginSettingsWebserverRequestTestCaseTrait;
    use ModifyValueFixtureEndpointWebserverRequestTestCaseTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $this->modifyPluginSettingsSetUp();
    }

    protected function tearDown(): void
    {
        $this->modifyPluginSettingsTearDown();

        parent::tearDown();
    }

    protected function executePluginSettingsSetUpTearDown(string|int $dataName): bool
    {
        return $this->executeSetUpTearDownUnlessIsOriginalTestCase($dataName);
    }

    /**
     * @param array<string,mixed> $providerItems
     * @return array<string,mixed>
     */
    protected function customizeProviderEndpointEntries(array $providerItems): array
    {
        return $this->reorderProviderEndpointEntriesToExecuteOriginalTestFirst($providerItems);
    }
}
