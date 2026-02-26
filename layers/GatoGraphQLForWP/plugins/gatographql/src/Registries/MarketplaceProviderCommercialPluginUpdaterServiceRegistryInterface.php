<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Registries;

use GatoGraphQL\GatoGraphQL\Marketplace\MarketplaceProviderCommercialPluginUpdaterServiceInterface;

interface MarketplaceProviderCommercialPluginUpdaterServiceRegistryInterface
{
    public function addMarketplaceProviderCommercialPluginUpdaterService(
        MarketplaceProviderCommercialPluginUpdaterServiceInterface $service
    ): void;

    /**
     * @return MarketplaceProviderCommercialPluginUpdaterServiceInterface[]
     */
    public function getMarketplaceProviderCommercialPluginUpdaterServices(): array;

    public function getMarketplaceProviderCommercialPluginUpdaterServiceForLicense(
        string $licenseKey
    ): MarketplaceProviderCommercialPluginUpdaterServiceInterface;
}
