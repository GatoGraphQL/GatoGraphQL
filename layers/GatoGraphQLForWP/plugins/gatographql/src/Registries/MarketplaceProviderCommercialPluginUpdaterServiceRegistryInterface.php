<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Registries;

use GatoGraphQL\GatoGraphQL\MarketplaceProviders\MarketplaceProviderCommercialPluginUpdaterServiceInterface;

interface MarketplaceProviderCommercialPluginUpdaterServiceRegistryInterface
{
    public function addMarketplaceProviderCommercialPluginUpdaterService(
        MarketplaceProviderCommercialPluginUpdaterServiceInterface $service
    ): void;

    /**
     * @return MarketplaceProviderCommercialPluginUpdaterServiceInterface[]
     */
    public function getMarketplaceProviderCommercialPluginUpdaterServices(bool $sortByPriority = true): array;
}
