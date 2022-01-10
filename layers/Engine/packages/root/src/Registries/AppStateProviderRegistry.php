<?php

declare(strict_types=1);

namespace PoP\Root\Registries;

use PoP\Root\Component\ComponentAppStateInterface;

class AppStateProviderRegistry implements AppStateProviderRegistryInterface
{
    /**
     * @var ComponentAppStateInterface[]
     */
    protected array $appStateProviders = [];

    public function addAppStateProvider(ComponentAppStateInterface $appStateProvider): void
    {
        $this->appStateProviders[] = $appStateProvider;
    }
    /**
     * @return ComponentAppStateInterface[]
     */
    public function getAppStateProviders(): array
    {
        return $this->appStateProviders;
    }
}
