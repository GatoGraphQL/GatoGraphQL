<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Unit;

abstract class AbstractFixtureQueryExecutionGraphQLServerTestCase extends AbstractGraphQLServerTestCase
{
    use FixtureTestCaseTrait;

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
     */
    public function fixtureGraphQLServerExecutionProvider(): array
    {
        $fixtureFolder = $this->getFixtureFolder();
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
            $graphQLResponseFile = $filePath . \DIRECTORY_SEPARATOR . $fileName . '.json';
            $graphQLVariablesFile = $filePath . \DIRECTORY_SEPARATOR . $fileName . '.var.json';
            if (!\file_exists($graphQLVariablesFile)) {
                $graphQLVariablesFile = null;
            }

            /**
             * If the test is organized under a subfolder (such as "Success" or "Error"),
             * append it to the named dataset
             */
            $graphQLFilesSubfolder = substr($filePath, strlen($fixtureFolder) + 1);
            $namedDataset = ($graphQLFilesSubfolder !== '' ? $graphQLFilesSubfolder . \DIRECTORY_SEPARATOR : '') . $fileName;
            $providerItems[$namedDataset] = [
                $graphQLQueryFile,
                $graphQLResponseFile,
                $graphQLVariablesFile,
                null,
            ];

            /**
             * Retrieve additional GraphQL responses to execute some "operationName"
             */
            $graphQLResponseForOperationFileNameFileInfos = $this->findFilesInDirectory(
                $fixtureFolder,
                [$fileName . ':*.json'],
                ['*.disabled.json'],
            );
            foreach ($graphQLResponseForOperationFileNameFileInfos as $graphQLResponseForOperationFileInfo) {
                $graphQLResponseForOperationFile = $graphQLResponseForOperationFileInfo->getRealPath();
                $operationFileName = $graphQLResponseForOperationFileInfo->getFilenameWithoutExtension();
                $operationName = substr($operationFileName, strpos($operationFileName, ':') + 1);
                $providerItems["${namedDataset}:${operationName}"] = [
                    $graphQLQueryFile,
                    $graphQLResponseForOperationFile,
                    $graphQLVariablesFile,
                    $operationName,
                ];
            }
        }
        return $providerItems;
    }

    /**
     * Directory under the fixture files are placed
     */
    abstract protected function getFixtureFolder(): string;
}
