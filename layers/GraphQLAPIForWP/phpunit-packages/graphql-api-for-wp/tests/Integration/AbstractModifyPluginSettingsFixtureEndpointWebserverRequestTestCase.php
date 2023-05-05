<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use PHPUnitForGatoGraphQL\WebserverRequests\ModifyPluginSettingsWebserverRequestTestCaseTrait;

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

    protected function executePluginSettingsSetUpTearDown(string $dataName): bool
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
