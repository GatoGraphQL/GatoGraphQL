<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Symplify\MonorepoBuilder\DataSources;

class DowngradeRectorDataSource
{
    public function __construct(protected string $rootDir)
    {
    }

    /**
     * @return string[]
     */
    public function getAdditionalDowngradeRectorFiles(): array
    {
        return [
            $this->rootDir . '/config/rector/downgrade/monorepo/chained-rules/rector-cacheitem.php',
            $this->rootDir . '/config/rector/downgrade/monorepo/chained-rules/rector-arrowfunction-mixedtype.php',
            $this->rootDir . '/config/rector/downgrade/monorepo/chained-rules/rector-arrowfunction-uniontype.php',
        ];
    }
}
