<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Symplify\MonorepoBuilder\DataSources;

class AdditionalIntegrationTestPluginsDataSource
{
    public function __construct(protected string $rootDir)
    {
    }

    /**
     * @return string[]
     */
    public function getAdditionalIntegrationTestPlugins(): array
    {
        return [];
    }
}
