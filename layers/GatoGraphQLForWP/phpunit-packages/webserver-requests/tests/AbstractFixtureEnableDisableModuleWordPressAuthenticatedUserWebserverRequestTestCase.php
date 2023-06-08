<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\WebserverRequests;

use GraphQLByPoP\GraphQLServer\Unit\FixtureQueryExecutionGraphQLServerTestCaseTrait;
use GraphQLByPoP\GraphQLServer\Unit\FixtureTestCaseTrait;

use function file_get_contents;

/**
 * Test that enabling/disabling a module works well.
 */
abstract class AbstractFixtureEnableDisableModuleWordPressAuthenticatedUserWebserverRequestTestCase extends AbstractEnableDisableModuleWordPressAuthenticatedUserWebserverRequestTestCase
{
    use FixtureTestCaseTrait;
    use FixtureQueryExecutionGraphQLServerTestCaseTrait;

    /**
     * Since PHPUnit v10, this is not possible anymore!
     */
    // final public function dataSetAsString(): string
    // {
    //     return $this->addFixtureFolderInfo(parent::dataSetAsString());
    // }

    /**
     * @return array<string,array<string,mixed>> An array of [$moduleName => ['query' => "...", 'response-enabled' => "...", 'response-disabled' => "..."]]
     */
    protected static function getModuleNameEntries(): array
    {
        $moduleEntries = [];
        $fixtureFolder = static::getFixtureFolder();

        $graphQLQueryFileNameFileInfos = static::findFilesInDirectory(
            $fixtureFolder,
            ['*.gql'],
            ['*.disabled.gql']
        );

        $moduleEntries = [];
        foreach ($graphQLQueryFileNameFileInfos as $graphQLQueryFileInfo) {
            $query = $graphQLQueryFileInfo->getContents();

            /**
             * From the GraphQL query file name, generate the remaining file names
             */
            $fileName = $graphQLQueryFileInfo->getFilenameWithoutExtension();
            $filePath = $graphQLQueryFileInfo->getPath();
            $moduleEnabledGraphQLResponseFile = static::getGraphQLResponseFile($filePath, $fileName . ':enabled');
            if (!\file_exists($moduleEnabledGraphQLResponseFile)) {
                static::throwFileNotExistsException($moduleEnabledGraphQLResponseFile);
            }
            $moduleDisabledGraphQLResponseFile = static::getGraphQLResponseFile($filePath, $fileName . ':disabled');
            if (!\file_exists($moduleDisabledGraphQLResponseFile)) {
                static::throwFileNotExistsException($moduleDisabledGraphQLResponseFile);
            }

            // The module name is created by the folder (module vendor) + fileName (module name)
            $moduleVendor = substr($filePath, strlen($fixtureFolder . '/'));
            $moduleName = $moduleVendor . '/' . $fileName;
            $moduleEntries[$moduleName] = [
                'query' => $query,
                'response-enabled' => file_get_contents($moduleEnabledGraphQLResponseFile),
                'response-disabled' => file_get_contents($moduleDisabledGraphQLResponseFile),
                'endpoint' => static::getModuleEndpoint($fileName),
            ];
        }
        return static::customizeModuleEntries($moduleEntries);
    }

    /**
     * @param array<string,array<string,mixed>> $moduleEntries
     * @return array<string,array<string,mixed>>
     */
    protected static function customizeModuleEntries(array $moduleEntries): array
    {
        return $moduleEntries;
    }

    protected static function getModuleEndpoint(string $fileName): ?string
    {
        return static::getEndpoint();
    }

    /**
     * The combination of folder and filename create the moduleID
     */
    protected function getModuleID(string $dataName): string
    {
        return str_replace(
            ['/', ':enabled', ':disabled'],
            ['_', '', ''],
            $dataName
        );
    }
}
