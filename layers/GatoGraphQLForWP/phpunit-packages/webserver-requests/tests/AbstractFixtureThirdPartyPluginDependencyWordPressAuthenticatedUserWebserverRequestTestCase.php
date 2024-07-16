<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\WebserverRequests;

use GraphQLByPoP\GraphQLServer\Unit\FixtureTestCaseTrait;
use PHPUnitForGatoGraphQL\GatoGraphQL\Integration\FixtureWebserverRequestTestCaseTrait;

use function file_get_contents;

/**
 * Test that enabling/disabling a required 3rd-party plugin works well.
 */
abstract class AbstractFixtureThirdPartyPluginDependencyWordPressAuthenticatedUserWebserverRequestTestCase extends AbstractThirdPartyPluginDependencyWordPressAuthenticatedUserWebserverRequestTestCase
{
    use FixtureTestCaseTrait;
    use FixtureWebserverRequestTestCaseTrait;

    /**
     * Since PHPUnit v10, this is not possible anymore!
     */
    // final public function dataSetAsString(): string
    // {
    //     return $this->addFixtureFolderInfo(parent::dataSetAsString());
    // }

    /**
     * @return array<string,array<string,mixed>> An array of [$pluginName => ['query' => "...", 'response-enabled' => "...", 'response-disabled' => "..."]]
     */
    protected static function getPluginNameEntries(): array
    {
        $pluginEntries = [];
        $fixtureFolder = static::getFixtureFolder();
        $graphQLQueryFileNameFileInfos = static::findFilesInDirectory(
            $fixtureFolder,
            ['*.gql'],
            ['*.disabled.gql']
        );

        $pluginEntries = [];
        foreach ($graphQLQueryFileNameFileInfos as $graphQLQueryFileInfo) {
            $query = $graphQLQueryFileInfo->getContents();

            /**
             * From the GraphQL query file name, generate the remaining file names
             */
            $fileName = $graphQLQueryFileInfo->getFilenameWithoutExtension();
            $filePath = $graphQLQueryFileInfo->getPath();
            $pluginEnabledGraphQLResponseFile = $filePath . \DIRECTORY_SEPARATOR . $fileName . ':enabled.json';
            if (!\file_exists($pluginEnabledGraphQLResponseFile)) {
                static::throwFileNotExistsException($pluginEnabledGraphQLResponseFile);
            }
            $pluginDisabledGraphQLResponseFile = $filePath . \DIRECTORY_SEPARATOR . $fileName . ':disabled.json';
            if (!\file_exists($pluginDisabledGraphQLResponseFile)) {
                static::throwFileNotExistsException($pluginDisabledGraphQLResponseFile);
            }
            $pluginOnlyOneEnabledGraphQLResponseFile = $filePath . \DIRECTORY_SEPARATOR . $fileName . ':only-one-enabled.json';
            $pluginGraphQLVariablesFile = $filePath . \DIRECTORY_SEPARATOR . $fileName . '.var.json';

            // The plugin name is created by the folder (plugin vendor) + fileName (plugin name)
            $pluginVendor = substr($filePath, strlen($fixtureFolder . '/'));
            $pluginName = $pluginVendor . '/' . $fileName;
            $pluginEntries[$pluginName] = [
                'query' => $query,
                'response-enabled' => file_get_contents($pluginEnabledGraphQLResponseFile),
                'response-disabled' => file_get_contents($pluginDisabledGraphQLResponseFile),
            ];
            if (\file_exists($pluginOnlyOneEnabledGraphQLResponseFile)) {
                $pluginEntries[$pluginName]['response-only-one-enabled'] = file_get_contents($pluginOnlyOneEnabledGraphQLResponseFile);
            }
            if (\file_exists($pluginGraphQLVariablesFile)) {
                $pluginEntries[$pluginName]['variables'] = static::getGraphQLVariables($pluginGraphQLVariablesFile);

                /**
                 * Check if there are additional response entries (with different vars)
                 * for the same query. For that, search for all items of type :{number}.var.json,
                 * and add those entries with the same query.
                 */
                $additionalPluginGraphQLVariablesFiles = static::findFilesInDirectory(
                    $filePath,
                    [$fileName . ':*.var.json'],
                    ['*.disabled.var.json']
                );
                foreach ($additionalPluginGraphQLVariablesFiles as $additionalPluginGraphQLVariablesFile) {
                    $additionalFileName = $additionalPluginGraphQLVariablesFile->getFilenameWithoutExtension();
                    $additionalFileNumberWithSuffix = substr($additionalFileName, strpos($additionalFileName, ':') + 1);
                    /** @var int */
                    $dotVarPos = strpos($additionalFileNumberWithSuffix, '.var');
                    $additionalFileNumber = substr($additionalFileNumberWithSuffix, 0, $dotVarPos);
                    if (!is_numeric($additionalFileNumber)) {
                        continue;
                    }
                    $additionalPluginName = $pluginName . ':' . $additionalFileNumber;
                    $pluginEntries[$additionalPluginName] = [
                        'query' => $query,
                        'variables' => static::getGraphQLVariables($additionalPluginGraphQLVariablesFile->getRealPath()),
                    ];
                    // For the variations, make the "enable" and "disable" responses optional
                    $additionalPluginEnabledGraphQLResponseFile = $filePath . \DIRECTORY_SEPARATOR . $fileName . ':' . $additionalFileNumber . ':enabled.json';
                    if (\file_exists($additionalPluginEnabledGraphQLResponseFile)) {
                        $pluginEntries[$additionalPluginName]['response-enabled'] = file_get_contents($additionalPluginEnabledGraphQLResponseFile);
                    }
                    $additionalPluginDisabledGraphQLResponseFile = $filePath . \DIRECTORY_SEPARATOR . $fileName . ':' . $additionalFileNumber . ':disabled.json';
                    if (\file_exists($additionalPluginDisabledGraphQLResponseFile)) {
                        $pluginEntries[$additionalPluginName]['response-disabled'] = file_get_contents($additionalPluginDisabledGraphQLResponseFile);
                    }
                    $additionalPluginOnlyOneEnabledGraphQLResponseFile = $filePath . \DIRECTORY_SEPARATOR . $fileName . ':only-one-enabled.json';
                    if (\file_exists($additionalPluginOnlyOneEnabledGraphQLResponseFile)) {
                        $pluginEntries[$additionalPluginName]['response-only-one-enabled'] = file_get_contents($additionalPluginOnlyOneEnabledGraphQLResponseFile);
                    }
                }
            }
        }
        return static::customizePluginNameEntries($pluginEntries);
    }

    /**
     * @param array<string,array<string,mixed>> $pluginEntries
     * @return array<string,array<string,mixed>>
     */
    protected static function customizePluginNameEntries(array $pluginEntries): array
    {
        return $pluginEntries;
    }
}
