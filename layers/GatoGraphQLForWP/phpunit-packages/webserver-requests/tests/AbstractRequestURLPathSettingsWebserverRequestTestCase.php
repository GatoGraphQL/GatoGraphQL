<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\WebserverRequests;

use GatoGraphQL\GatoGraphQL\Constants\ModuleSettingOptions;

/**
 * Test that accepting a URL produces the expected status code
 */
abstract class AbstractRequestURLPathSettingsWebserverRequestTestCase extends AbstractModifyPluginSettingsWebserverRequestTestCase
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
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('providePathEntries')]
    public function testPathsUpdated(
        string $dataItem,
    ): void {
        $this->doTestPathsUpdated(
            $this->getNewPath($dataItem),
            $this->getPreviousPath($this->previousValue)
        );
    }

    protected function doTestPathsUpdated(
        string $newPath,
        string $previousPath,
    ): void {
        $this->doTestEnabledOrDisabledPath($newPath, 200, 'application/json', true);
        $this->doTestEnabledOrDisabledPath($previousPath, 404, null, false);
    }

    protected function getNewPath(string $dataItem): string
    {
        return $dataItem;
    }

    protected function getPreviousPath(string $previousValue): string
    {
        return $previousValue;
    }

    /**
     * @return array<string,string[]> Array of 1 element: [ ${newPath} ]
     */
    abstract public static function providePathEntries(): array;

    protected function getPluginSettingsNewValue(): mixed
    {
        $data = $this->providedData();
        return $data[0];
    }
}
