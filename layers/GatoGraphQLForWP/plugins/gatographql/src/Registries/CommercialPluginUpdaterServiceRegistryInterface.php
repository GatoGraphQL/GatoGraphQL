<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Registries;

use GatoGraphQL\GatoGraphQL\Marketplace\MarketplaceProviderCommercialPluginUpdaterServiceInterface;

interface CommercialPluginUpdaterServiceRegistryInterface
{
    public function addCommercialPluginUpdaterService(
        MarketplaceProviderCommercialPluginUpdaterServiceInterface $service
    ): void;

    /**
     * @return MarketplaceProviderCommercialPluginUpdaterServiceInterface[]
     */
    public function getCommercialPluginUpdaterServices(): array;

    public function getCommercialPluginUpdaterServiceForLicense(
        string $licenseKey
    ): MarketplaceProviderCommercialPluginUpdaterServiceInterface;
}
