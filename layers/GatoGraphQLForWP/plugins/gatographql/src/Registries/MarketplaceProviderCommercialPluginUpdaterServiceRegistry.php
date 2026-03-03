<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Registries;

use GatoGraphQL\GatoGraphQL\MarketplaceProviders\MarketplaceProviderCommercialPluginUpdaterServiceInterface;

use function usort;

class MarketplaceProviderCommercialPluginUpdaterServiceRegistry implements MarketplaceProviderCommercialPluginUpdaterServiceRegistryInterface
{
    /**
     * @var array<int,MarketplaceProviderCommercialPluginUpdaterServiceInterface>
     */
    protected array $marketplaceProviderCommercialPluginUpdaterServices = [];

    public function addMarketplaceProviderCommercialPluginUpdaterService(
        MarketplaceProviderCommercialPluginUpdaterServiceInterface $marketplaceProviderCommercialPluginUpdaterService
    ): void {
        $this->marketplaceProviderCommercialPluginUpdaterServices[] = $marketplaceProviderCommercialPluginUpdaterService;
    }

    /**
     * @return MarketplaceProviderCommercialPluginUpdaterServiceInterface[]
     */
    public function getMarketplaceProviderCommercialPluginUpdaterServices(bool $sortByPriority = true): array
    {
        $services = $this->marketplaceProviderCommercialPluginUpdaterServices;
        if ($sortByPriority) {
            usort(
                $services,
                static fn (
                    MarketplaceProviderCommercialPluginUpdaterServiceInterface $a,
                    MarketplaceProviderCommercialPluginUpdaterServiceInterface $b
                ): int => $a->getPriority() <=> $b->getPriority()
            );
        }
        return $services;
    }
}
