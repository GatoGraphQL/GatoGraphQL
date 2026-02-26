<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Registries;

use GatoGraphQL\GatoGraphQL\MarketplaceProviders\MarketplaceProviderCommercialExtensionActivationServiceInterface;

interface CommercialExtensionActivationServiceRegistryInterface
{
    public function addCommercialExtensionActivationService(
        MarketplaceProviderCommercialExtensionActivationServiceInterface $service
    ): void;

    /**
     * @return MarketplaceProviderCommercialExtensionActivationServiceInterface[]
     */
    public function getCommercialExtensionActivationServices(): array;

    public function getCommercialExtensionActivationServiceForLicense(
        string $licenseKey
    ): MarketplaceProviderCommercialExtensionActivationServiceInterface;
}

