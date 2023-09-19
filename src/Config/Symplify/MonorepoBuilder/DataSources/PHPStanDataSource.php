<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Symplify\MonorepoBuilder\DataSources;

class PHPStanDataSource
{
    public function getLevel(): int
    {
        return 8;
    }

    /**
     * @return string[]
     */
    public function getUnmigratedFailingPackages(): array
    {
        return [
        ];
    }
}
