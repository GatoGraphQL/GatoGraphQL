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

    public function fixtureGraphQLServerExecutionProvider(): array
    {
        $directory = $this->getFixtureFolder();

        /**
         * Retrieve all files:
         *
         * - GraphQL queries: ending in ".gql" or ".graphql"
         *
         * Each of these files will need to have corresponding file(s)
         * in the same folder, all of them sharing the same file name:
         *
         * - GraphQL response: ending in "${fileName}.json"
         * - GraphQL variables: ending in "${fileName}.var.json"
         */
        $graphQLQueryFileNameFileInfos = $this->findFilesInDirectory($directory, ['*.gql', '*.graphql']);
        // $graphQLResponseFileNameFileInfos = $this->placeFileInfosUnderFileName(
        //     $this->findFilesInDirectory($directory, ['*.json'], ['*.var.json'])
        // );
        // $graphQLVariableFileNameFileInfos = $this->placeFileInfosUnderFileName(
        //     $this->findFilesInDirectory($directory, ['*.var.json'])
        // );

        /**
         * Pair up these files among themselves. They must all share the
         * same fileName
         */
        $providerItems = [];
        foreach ($graphQLQueryFileNameFileInfos as $graphQLQueryFileInfo) {
            $fileName = $graphQLQueryFileInfo->getFilenameWithoutExtension();
            $filePath = $graphQLQueryFileInfo->getPath();
            $graphQLQueryFile = $graphQLQueryFileInfo->getRealPath();
            $graphQLResponseFile = $filePath . \DIRECTORY_SEPARATOR . $fileName . '.json';
            $graphQLVariablesFile = $filePath . \DIRECTORY_SEPARATOR . $fileName . '.var.json';
            if (!\file_exists($graphQLVariablesFile)) {
                $graphQLVariablesFile = null;
            }
            $providerItems[] = [$graphQLQueryFile, $graphQLResponseFile, $graphQLVariablesFile];
            // /**
            //  * The GraphQL response file is mandatory, but the variables one is optional
            //  */
            // $graphQLResponseFileInfo = $graphQLResponseFileNameFileInfos[$fileName] ?? null;
            // if ($graphQLResponseFileInfo === null) {
            //     // Stop here and mark this test as incomplete.
            //     $this->markTestIncomplete(
            //         sprintf(
            //             'File "%s.json" (with the expected GraphQL response) is missing.',
            //             $fileName
            //         )
            //     );
            //     continue;
            // }
            // $graphQLVariableFileInfo = $graphQLVariableFileNameFileInfos[$fileName] ?? null;

            // /**
            //  * We have identified all the files. Extract their content, and pass it upwards
            //  * via the provider.
            //  */
            // $graphQLQuery = file_get_contents($graphQLQueryFileInfo->getRealPath());
            // $graphQLResponse = file_get_contents($graphQLResponseFileInfo->getRealPath());
            // $graphQLVariables = $graphQLVariableFileInfo !== null ? file_get_contents($graphQLVariableFileInfo->getRealPath()) : [];
            // $providerItems[] = [$graphQLQuery, $graphQLResponse, $graphQLVariables];
        }
        return $providerItems;
    }

    // /**
    //  * @return Iterator<array<int, SplFileInfo>>
    //  */
    // public function provideData(): Iterator
    // {
    //     return $this->yieldDirectory($this->getFixtureFolder());
    // }

    // /**
    //  * @return Iterator<array<int, SplFileInfo>>
    //  */
    // protected function yieldDirectory(string $directory): Iterator
    // {
    //     $fileInfos = $this->findFilesInDirectory($directory);
    //     return $this->yieldFileInfos($fileInfos);
    // }

    /**
     * @return SplFileInfo[]
     */
    protected function findFilesInDirectory(string $directory, array $fileNames, array $notFileNames = []): array
    {
        $finder = Finder::create()->in($directory)->files()->name($fileNames)->notName($notFileNames);
        $fileInfos = iterator_to_array($finder);
        return array_values($fileInfos);
    }

    // /**
    //  * @param SplFileInfo[] $fileInfos
    //  * @return Iterator<array<int, SplFileInfo>>
    //  */
    // protected function yieldFileInfos(array $fileInfos): Iterator
    // {
    //     foreach ($fileInfos as $fileInfo) {
    //         yield [$fileInfo];
    //     }
    // }

    /**
     * Directory under the fixture files are placed
     */
    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/Fixture';
    }

    // /**
    //  * @param SplFileInfo[] $fileInfos
    //  * @return array<string,SplFileInfo>
    //  */
    // protected function placeFileInfosUnderFileName(array $fileInfos): array
    // {
    //     $fileNameFileInfos = [];
    //     foreach ($fileInfos as $fileInfo) {
    //         $fileNameWithExtension = $fileInfo->getRelativePathname();
    //         $fileNameFileInfos[$fileNameWithExtension] = $fileInfo;
    //     }
    //     return $fileNameFileInfos;
    // }
}
