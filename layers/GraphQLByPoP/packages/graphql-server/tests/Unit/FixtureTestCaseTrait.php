<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Unit;

use RuntimeException;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

trait FixtureTestCaseTrait
{
    /**
     * Directory under the fixture files are placed
     */
    abstract protected function getFixtureFolder(): string;

    /**
     * @return SplFileInfo[]
     */
    protected function findFilesInDirectory(string $directory, array $fileNames, array $notFileNames = []): array
    {
        $finder = Finder::create()->in($directory)->files()->name($fileNames)->notName($notFileNames);
        // Allow fixtures to be named with cardinal numbers, to execute the tests in a desired order
        $finder->sortByName(true);
        $fileInfos = iterator_to_array($finder);
        return array_values($fileInfos);
    }

    /**
     * @throws RuntimeException
     */
    protected function throwFileNotExistsException(string $file): never
    {
        throw new RuntimeException(
            sprintf(
                'File "%s" does not exist',
                $file
            )
        );
    }

    protected function addFixtureFolderInfo(string $string): string
    {
        return sprintf(
            ' (fixture folder: "%s")%s',
            $this->getFixtureFolder(),
            $string,
        );
    }
}
