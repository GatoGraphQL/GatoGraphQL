<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WebserverRequests;

use GraphQLAPI\GraphQLAPI\Constants\ModuleSettingOptions;

/**
 * Test that enabling/disabling clients (GraphiQL/Voyager)
 * in Custom Endpoints works well
 */
abstract class AbstractClientPathSettingsWebserverRequestTest extends AbstractModifyPluginSettingsWebserverRequestTest
{
    use ClientWebserverRequestTestCaseTrait;

    protected function getSettingsKey(): string
    {
        return ModuleSettingOptions::PATH;
    }

    /**
     * Test that:
     *
     * 1. The client under the new path returns a 200
     * 2. The client under the old path returns a 404
     *
     * @dataProvider provideClientPathEntries
     */
    public function testClientPathsUpdated(
        string $newClientPath,
        string $previousClientPath,
    ): void {
        $this->testEnabledOrDisabledClients($newClientPath, 200, true);
        $this->testEnabledOrDisabledClients($previousClientPath, 404, false);
    }

    /**
     * @return array<string,string[]> Array of 2 elements: [ ${newClientPath}, ${previousClientPath} ]
     */
    abstract protected function provideClientPathEntries(): array;

    protected function getPluginSettingsNewValue(array $data): mixed
    {
        return $data[0];
    }

    protected function getPluginSettingsOriginalValue(array $data): mixed
    {
        return $data[1];
    }
}
