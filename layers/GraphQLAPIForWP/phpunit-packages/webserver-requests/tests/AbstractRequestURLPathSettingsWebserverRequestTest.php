<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WebserverRequests;

use GraphQLAPI\GraphQLAPI\Constants\ModuleSettingOptions;

/**
 * Test that accepting a URL produces the expected status code
 */
abstract class AbstractRequestURLPathSettingsWebserverRequestTest extends AbstractModifyPluginSettingsWebserverRequestTest
{
    use RequestURLWebserverRequestTestCaseTrait;

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
     * @dataProvider providePathEntries
     */
    public function testPathsUpdated(
        string $dataItem,
    ): void {
        $newClientPath = $this->getNewClientPath($dataItem);
        $this->testURLRequestProducesExpectedStatusCode($newClientPath, 200, true);
        $this->testURLRequestProducesExpectedStatusCode($this->previousValue, 404, false);
    }

    protected function getNewClientPath(string $dataItem): string
    {
        return $dataItem;
    }

    /**
     * @return array<string,string[]> Array of 1 element: [ ${newClientPath} ]
     */
    abstract protected function providePathEntries(): array;

    protected function getPluginSettingsNewValue(): mixed
    {
        $data = $this->getProvidedData();
        return $data[0];
    }
}
