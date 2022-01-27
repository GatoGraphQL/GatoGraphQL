<?php

declare(strict_types=1);

namespace PoP\Root\Registries;

use PoP\Root\State\AppStateProviderInterface;

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

    /**
     * @return AppStateProviderInterface[]
     */
    public function getEnabledAppStateProviders(): array
    {
        return array_values(array_filter(
            $this->getAppStateProviders(),
            fn (AppStateProviderInterface $service) => $service->isServiceEnabled()
        ));
    }
}
