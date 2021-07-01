<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Symplify\MonorepoBuilder\DataSources;

class DowngradeRectorDataSource
{
    function __construct(protected string $rootDir)
    {        
    }

    /**
     * @return string[]
     */
    public function getAdditionalDowngradeRectorDataSourceFiles(): array
    {
        return array_map(
            fn (string $file) => $this->getRelativePathToDataSourceFiles() . $file,
            $this->getAdditionalDowngradeRectorDataSourceFileRelativePaths()
        );
    }

    protected function getRelativePathToDataSourceFiles(): string
    {
        return $this->rootDir;
    }

    /**
     * @return string[]
     */
    protected function getAdditionalDowngradeRectorDataSourceFileRelativePaths(): array
    {
        return [
            '/config/rector/downgrades/monorepo/chained-rules/rector-cacheitem.php',
            '/ci/downgrades/rector-downgrade-code-hacks-ArrowFnMixedType.php',
            '/ci/downgrades/rector-downgrade-code-hacks-ArrowFnUnionType.php',
        ];
    }
}
