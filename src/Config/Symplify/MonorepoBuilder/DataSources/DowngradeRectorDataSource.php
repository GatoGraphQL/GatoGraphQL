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
    public function getAdditionalDowngradeRectorBeforeFiles(): array
    {
        return [
            $this->rootDir . '/config/rector/downgrade/monorepo/chained-rules/rector-change-if-or-return-earlyreturn.php',
        ];
    }

    /**
     * @return string[]
     */
    public function getAdditionalDowngradeRectorAfterFiles(): array
    {
        return [
            $this->rootDir . '/config/rector/downgrade/monorepo/chained-rules/rector-arrowfunction-mixedtype.php',
            $this->rootDir . '/config/rector/downgrade/monorepo/chained-rules/rector-arrowfunction-uniontype.php',
            $this->rootDir . '/config/rector/downgrade/monorepo/chained-rules/rector-covariant-return-type.php',
        ];
    }
}
