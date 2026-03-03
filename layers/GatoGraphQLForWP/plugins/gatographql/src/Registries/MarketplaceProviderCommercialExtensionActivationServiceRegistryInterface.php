<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Registries;

use GatoGraphQL\GatoGraphQL\MarketplaceProviders\MarketplaceProviderCommercialExtensionActivationServiceInterface;

interface MarketplaceProviderCommercialExtensionActivationServiceRegistryInterface
{
    public function addMarketplaceProviderCommercialExtensionActivationService(
        MarketplaceProviderCommercialExtensionActivationServiceInterface $service
    ): void;

    /**
     * @return MarketplaceProviderCommercialExtensionActivationServiceInterface[]
     */
    public function getMarketplaceProviderCommercialExtensionActivationServices(): array;

    public function getMarketplaceProviderCommercialExtensionActivationServiceForLicense(
        string $licenseKey
    ): MarketplaceProviderCommercialExtensionActivationServiceInterface;
}
