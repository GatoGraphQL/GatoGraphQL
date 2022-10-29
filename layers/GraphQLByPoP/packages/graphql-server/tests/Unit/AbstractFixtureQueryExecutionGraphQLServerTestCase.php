<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Unit;

abstract class AbstractFixtureQueryExecutionGraphQLServerTestCase extends AbstractGraphQLServerTestCase
{
    use FixtureQueryExecutionGraphQLServerTestCaseTrait;

    public function getDataSetAsString(bool $includeData = true): string
    {
        return $this->addFixtureFolderInfo(parent::getDataSetAsString($includeData));
    }

    /**
     * @dataProvider fixtureGraphQLServerExecutionProvider
     */
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
    public function fixtureGraphQLServerExecutionProvider(): array
    {
        /**
         * Source folder for the .gql files and,
         * by default, their .json responses
         */
        $fixtureFolder = $this->getFixtureFolder();
        /**
         * Possibly define a different folder for the .json responses
         */
        $responseFixtureFolder = $this->getResponseFixtureFolder();

        /**
         * Retrieve all non-disabled GraphQL files
         */
        $graphQLQueryFileNameFileInfos = $this->findFilesInDirectory(
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
            $graphQLFilesSubfolder = substr($filePath, strlen($responseFixtureFolder) + 1);
            $dataName = ($graphQLFilesSubfolder !== '' ? $graphQLFilesSubfolder . \DIRECTORY_SEPARATOR : '') . $fileName;

            /**
             * Make sure the test is not temporarily disabled
             */
            if ($this->isProviderTestDisabled($dataName)) {
                continue;
            }

            $graphQLResponseFile = $this->getGraphQLResponseFile($filePath, $fileName);
            $graphQLVariablesFile = $this->getGraphQLVariablesFile($filePath, $fileName);
            if (!\file_exists($graphQLVariablesFile)) {
                $graphQLVariablesFile = null;
            }

            $mainFixtureOperationName = $this->getMainFixtureOperationName($dataName);

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
            $graphQLResponseForOperationFileNameFileInfos = $this->findFilesInDirectory(
                $responseFixtureFolder . ($graphQLFilesSubfolder !== '' ? \DIRECTORY_SEPARATOR . $graphQLFilesSubfolder : ''),
                [$fileName . ':*.json'],
                ['*.disabled.json', '*.var.json'],
            );
            foreach ($graphQLResponseForOperationFileNameFileInfos as $graphQLResponseForOperationFileInfo) {
                $graphQLResponseForOperationFile = $graphQLResponseForOperationFileInfo->getRealPath();
                $operationFileName = $graphQLResponseForOperationFileInfo->getFilenameWithoutExtension();
                $operationName = substr($operationFileName, strpos($operationFileName, ':') + 1);
                $graphQLVariablesForOperationFile = $this->getGraphQLVariablesFile($filePath, $fileName . ':' . $operationName);
                if (!\file_exists($graphQLVariablesForOperationFile)) {
                    $graphQLVariablesForOperationFile = $graphQLVariablesFile;
                }
                $providerItems["${dataName}:${operationName}"] = [
                    $graphQLQueryFile,
                    $graphQLResponseForOperationFile,
                    $graphQLVariablesForOperationFile,
                    is_numeric($operationName) ? $mainFixtureOperationName : $operationName,
                ];
            }
        }
        return $providerItems;
    }

    protected function getMainFixtureOperationName(string $dataName): ?string
    {
        return null;
    }

    protected function isProviderTestDisabled(string $dataName): bool
    {
        return false;
    }
}
