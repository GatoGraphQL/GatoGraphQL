<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Unit;

use RuntimeException;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

trait FixtureTestCaseTrait
{
    /**
     * Directory under which the fixture files are placed
     */
    abstract protected static function getFixtureFolder(): string;

    /**
     * Directory under which the response fixture files are placed.
     * By default it's the same where the query fixture files are.
     */
    protected static function getResponseFixtureFolder(): string
    {
        return static::getFixtureFolder();
    }

    /**
     * @return SplFileInfo[]
     * @param string[] $fileNames
     * @param string[] $notFileNames
     */
    protected static function findFilesInDirectory(string $directory, array $fileNames, array $notFileNames = []): array
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
    protected static function throwFileNotExistsException(string $file): void
    {
        throw new RuntimeException(
            sprintf(
                'File "%s" does not exist',
                $file
            )
        );
    }

    /**
     * Since PHPUnit v10, this is not possible anymore!
     */
    // protected function addFixtureFolderInfo(string $string): string
    // {
    //     return sprintf(
    //         ' (fixture folder: "%s")%s',
    //         static::getResponseFixtureFolder(),
    //         $string,
    //     );
    // }
}
