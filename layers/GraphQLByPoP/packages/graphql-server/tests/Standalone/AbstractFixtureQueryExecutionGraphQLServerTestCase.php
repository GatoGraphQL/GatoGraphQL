<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Standalone;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

abstract class AbstractFixtureQueryExecutionGraphQLServerTestCase extends AbstractGraphQLServerTestCase
{
    /**
     * @dataProvider fixtureGraphQLServerExecutionProvider
     */
    public function testFixtureGraphQLServerExecution(string $queryFile, string $expectedResponseFile, ?string $variablesFile = null, ?string $operationName = null): void
    {
        $this->assertFixtureGraphQLQueryExecution($queryFile, $expectedResponseFile, $variablesFile, $operationName);
    }

    /**
     * Retrieve all files under the "/Fixture" folder (by default):
     *
     *   - GraphQL queries: all files ending in ".gql" or ".graphql"
     *     (unless ending in ".disabled.gql" or ".disabled.graphql")
     *
     * Each of these files will need to have corresponding file(s)
     * in the same folder, sharing the same file name:
     *
     *   - GraphQL response: "${fileName}.json"
     *   - GraphQL variables: "${fileName}.var.json"
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
            // The operation name is provided by code, not by fixture
            $graphQLOperationName = $this->getGraphQLOperationName($fileName);

            /**
             * If the test is organized under a subfolder (such as "Success" or "Error"),
             * append it to the named dataset
             */
            $graphQLFilesSubfolder = substr($filePath, strlen($fixtureFolder) + 1);
            $namedDataset = ($graphQLFilesSubfolder !== '' ? $graphQLFilesSubfolder . \DIRECTORY_SEPARATOR : '') . $fileName;
            $providerItems[$namedDataset] = [$graphQLQueryFile, $graphQLResponseFile, $graphQLVariablesFile, $graphQLOperationName];
        }
        return $providerItems;
    }

    /**
     * @return SplFileInfo[]
     */
    protected function findFilesInDirectory(string $directory, array $fileNames, array $notFileNames = []): array
    {
        $finder = Finder::create()->in($directory)->files()->name($fileNames)->notName($notFileNames);
        $fileInfos = iterator_to_array($finder);
        return array_values($fileInfos);
    }

    /**
     * Directory under the fixture files are placed
     */
    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/Fixture';
    }

    /**
     * The operation name is provided by code, not by fixture
     */
    protected function getGraphQLOperationName(string $fileName): ?string
    {
        return null;
    }
}
