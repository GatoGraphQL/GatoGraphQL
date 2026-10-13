<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\WebserverRequests;

use GatoGraphQL\GatoGraphQL\Constants\ModuleSettingOptions;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * Test that an invalid value for an option that modifies
 * permalinks (such as the path of the single endpoint)
 * is not stored, and the endpoint can still be accessed
 * under its previous path
 */
abstract class AbstractInvalidURLPathSettingsWebserverRequestTestCase extends AbstractModifyPluginSettingsWebserverRequestTestCase
{
    use RequestURLWebserverRequestTestCaseTrait;

    protected function getSettingsKey(): string
    {
        return ModuleSettingOptions::PATH;
    }

    #[DataProvider('provideInvalidPathEntries')]
    public function testInvalidPathNotStored(
        string $moduleID,
        string $invalidPath,
    ): void {
        $this->assertEquals(
            $this->previousValue,
            $this->getPluginSettingsOriginalValue(),
            sprintf(
                'Invalid path \'%s\' must not be stored',
                $invalidPath
            )
        );
        $this->doTestPreviousPathIsAccessible($this->previousValue);
    }

    abstract protected function doTestPreviousPathIsAccessible(string $previousPath): void;

    /**
     * @return array<string,string[]> Array of 2 elements: [ ${moduleID}, ${invalidPath} ]
     */
    abstract public static function provideInvalidPathEntries(): array;

    protected function getModuleID(string $dataName): string
    {
        /** @var string */
        return $this->providedData()[0];
    }

    protected function getPluginSettingsNewValue(): mixed
    {
        return $this->providedData()[1];
    }
}
