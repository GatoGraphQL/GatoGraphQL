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
        return [
            $this->rootDir . '/ci/downgrades/rector-downgrade-code-hacks-CacheItem.php',
            $this->rootDir . '/ci/downgrades/rector-downgrade-code-hacks-ArrowFnMixedType.php',
            $this->rootDir . '/ci/downgrades/rector-downgrade-code-hacks-ArrowFnUnionType.php',
        ];
    }
}
