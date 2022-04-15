<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Standalone;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

trait FixtureTestCaseTrait
{
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
}
