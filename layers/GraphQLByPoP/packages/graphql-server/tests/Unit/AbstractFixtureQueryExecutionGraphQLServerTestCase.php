<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Unit;

use PHPUnit\Framework\Attributes\DataProvider;

abstract class AbstractFixtureQueryExecutionGraphQLServerTestCase extends AbstractGraphQLServerTestCase
{
    use FixtureQueryExecutionGraphQLServerTestCaseTrait;

    /**
     * Since PHPUnit v10, this is not possible anymore!
     */
    // final public function dataSetAsString(): string
    // {
    //     return $this->addFixtureFolderInfo(parent::dataSetAsString());
    // }

    #[DataProvider('fixtureGraphQLServerExecutionProvider')]
    public function testFixtureGraphQLServerExecution(string $queryFile, string $expectedResponseFile, ?string $variablesFile = null, ?string $operationName = null): void
    {
        $this->assertFixtureGraphQLQueryExecution($queryFile, $expectedResponseFile, $variablesFile, $operationName);
    }

    /**
     * Retrieve all files under the "/fixture" folder:
     *
     *   - GraphQL queries: all files ending in ".gql" or ".graphql"
     *     (unless ending in ".disabled.gql" or ".disabled.graphql")
     *
     * Each of these files will need to have corresponding file(s)
     * in the same folder, sharing the same file name:
     *
     *   - GraphQL response: "${fileName}.json"
     *   - GraphQL variables: "${fileName}.var.json"
     *
     * The operation to execute can by defined via additional responses,
     * with the "operationName" as part of the file name:
     *
     *   - GraphQL response for operationName: "${fileName}:${operationName}.json"
     *
     * @return mixed[]
     */
    public static function fixtureGraphQLServerExecutionProvider(): array
    {
        /**
         * Source folder for the .gql files and,
         * by default, their .json responses
         */
        $fixtureFolder = static::getFixtureFolder();
        /**
         * Possibly define a different folder for the .json responses
         */
        $responseFixtureFolder = static::getResponseFixtureFolder();

        /**
         * Retrieve all non-disabled GraphQL files
         */
        $graphQLQueryFileNameFileInfos = static::findFilesInDirectory(
            $fixtureFolder,
            ['*.gql', '*.graphql'],
            ['*.disabled.gql', '*.disabled.graphql']
        );

        $providerItems = [];
        foreach ($graphQLQueryFileNameFileInfos as $graphQLQueryFileInfo) {
            $graphQLQueryFile = $graphQLQueryFileInfo->getRealPath();

            /**
             * From the GraphQL query file name, generate the remaining file names
             */
            $fileName = $graphQLQueryFileInfo->getFilenameWithoutExtension();
            $filePath = $graphQLQueryFileInfo->getPath();

            /**
             * If the test is organized under a subfolder (such as "Success" or "Error"),
             * append it to the named dataset
             */
            $graphQLFilesSubfolder = substr($filePath, strlen($fixtureFolder) + 1);
            $dataName = ($graphQLFilesSubfolder !== '' ? $graphQLFilesSubfolder . \DIRECTORY_SEPARATOR : '') . $fileName;

            /**
             * Make sure the test is not temporarily disabled
             */
            if (static::isProviderTestDisabled($dataName)) {
                continue;
            }

            $graphQLResponseFile = static::getGraphQLResponseFile($filePath, $fileName);
            $graphQLVariablesFile = static::getGraphQLVariablesFile($filePath, $fileName);
            if (!\file_exists($graphQLVariablesFile)) {
                $graphQLVariablesFile = null;
            }

            $mainFixtureOperationName = static::getMainFixtureOperationName($dataName);

            $providerItems[$dataName] = [
                $graphQLQueryFile,
                $graphQLResponseFile,
                $graphQLVariablesFile,
                $mainFixtureOperationName,
            ];

            /**
             * Retrieve additional GraphQL responses to execute some "operationName",
             * unless it is numeric, in which case it just means "Execute the test
             * again (maybe with this other .var.json), it should have these
             * other results"
             */
            $graphQLResponseForOperationFileNameFileInfos = static::findFilesInDirectory(
                $responseFixtureFolder . ($graphQLFilesSubfolder !== '' ? \DIRECTORY_SEPARATOR . $graphQLFilesSubfolder : ''),
                [$fileName . ':*.json'],
                ['*.disabled.json', '*.var.json'],
            );
            foreach ($graphQLResponseForOperationFileNameFileInfos as $graphQLResponseForOperationFileInfo) {
                $graphQLResponseForOperationFile = $graphQLResponseForOperationFileInfo->getRealPath();
                $operationFileName = $graphQLResponseForOperationFileInfo->getFilenameWithoutExtension();
                $operationName = substr($operationFileName, strpos($operationFileName, ':') + 1);
                $graphQLVariablesForOperationFile = static::getGraphQLVariablesFile($filePath, $fileName . ':' . $operationName);
                if (!\file_exists($graphQLVariablesForOperationFile)) {
                    $graphQLVariablesForOperationFile = $graphQLVariablesFile;
                }
                $operationDataName = sprintf(
                    '%s:%s',
                    $dataName,
                    $operationName
                );
                $providerItems[$operationDataName] = [
                    $graphQLQueryFile,
                    $graphQLResponseForOperationFile,
                    $graphQLVariablesForOperationFile,
                    is_numeric($operationName) ? $mainFixtureOperationName : $operationName,
                ];
            }
        }
        return $providerItems;
    }

    protected static function getMainFixtureOperationName(string $dataName): ?string
    {
        return null;
    }

    protected static function isProviderTestDisabled(string $dataName): bool
    {
        return false;
    }
}
