<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Standalone;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

abstract class AbstractFixtureQueryExecutionGraphQLServerTestCase extends AbstractQueryExecutionGraphQLServerTestCase
{
    public function graphQLServerExecutionProvider(): array
    {
        $directory = $this->getFixtureFolder();

        /**
         * Retrieve all files:
         *
         * - GraphQL queries: ending in ".gql" or ".graphql"
         * - GraphQL responses: ending in ".json", but not ".var.json"
         * - GraphQL variables: ending in ".var.json"
         */
        $graphQLQueryFileNameFileInfos = $this->placeFileInfosUnderFileName(
            $this->findFilesInDirectory($directory, ['*.gql', '*.graphql'])
        );
        $graphQLResponseFileNameFileInfos = $this->placeFileInfosUnderFileName(
            $this->findFilesInDirectory($directory, ['*.json'], ['*.var.json'])
        );
        $graphQLVariableFileNameFileInfos = $this->placeFileInfosUnderFileName(
            $this->findFilesInDirectory($directory, ['*.var.json'])
        );

        /**
         * Pair up these files among themselves. They must all share the
         * same fileName
         */
        $providerItems = [];
        foreach ($graphQLQueryFileNameFileInfos as $fileName => $graphQLQueryFileInfo) {
            /**
             * The GraphQL response file is mandatory, but the variables one is optional
             */
            $graphQLResponseFileInfo = $graphQLResponseFileNameFileInfos[$fileName] ?? null;
            if ($graphQLResponseFileInfo === null) {
                // Stop here and mark this test as incomplete.
                $this->markTestIncomplete(
                    sprintf(
                        'File "%s.json" (with the expected GraphQL response) is missing.',
                        $fileName
                    )
                );
                continue;
            }
            $graphQLVariableFileInfo = $graphQLVariableFileNameFileInfos[$fileName] ?? null;
            $providerItems[] = [$graphQLQueryFileInfo, $graphQLResponseFileInfo, $graphQLVariableFileInfo];
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

    /**
     * @param SplFileInfo[] $fileInfos
     * @return array<string,SplFileInfo>
     */
    protected function placeFileInfosUnderFileName(array $fileInfos): array
    {
        $fileNameFileInfos = [];
        foreach ($fileInfos as $fileInfo) {
            $fileNameWithExtension = $fileInfo->getRelativePathname();
            $fileNameFileInfos[$fileNameWithExtension] = $fileInfo;
        }
        return $fileNameFileInfos;
    }
}
