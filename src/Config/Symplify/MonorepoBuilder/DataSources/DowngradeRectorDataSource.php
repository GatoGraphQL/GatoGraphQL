<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Symplify\MonorepoBuilder\DataSources;

class DowngradeRectorDataSource
{
    function __construct(protected string $dir)
    {        
    }

    /**
     * @return string[]
     */
    public function getAdditionalDowngradeRectorDataSourceFiles(): array
    {
        return [
            $this->dir . '/ci/downgrades/rector-downgrade-code-hacks-CacheItem.php',
            $this->dir . '/ci/downgrades/rector-downgrade-code-hacks-ArrowFnMixedType.php',
            $this->dir . '/ci/downgrades/rector-downgrade-code-hacks-ArrowFnUnionType.php',
        ];
    }
}
