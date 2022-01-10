<?php

declare(strict_types=1);

namespace PoP\Root\Registries;

use PoP\Root\Component\AppStateProviderInterface;

class AppStateProviderRegistry implements AppStateProviderRegistryInterface
{
    /**
     * @var AppStateProviderInterface[]
     */
    protected array $appStateProviders = [];

    public function addAppStateProvider(AppStateProviderInterface $appStateProvider): void
    {
        $this->appStateProviders[] = $appStateProvider;
    }
    /**
     * @return AppStateProviderInterface[]
     */
    public function getAppStateProviders(): array
    {
        return $this->appStateProviders;
    }
}
