<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Registries;

use GatoGraphQL\GatoGraphQL\MarketplaceProviders\MarketplaceProviderCommercialExtensionActivationServiceInterface;
use RuntimeException;

use function sprintf;
use function usort;

class MarketplaceProviderCommercialExtensionActivationServiceRegistry implements MarketplaceProviderCommercialExtensionActivationServiceRegistryInterface
{
    /**
     * @var MarketplaceProviderCommercialExtensionActivationServiceInterface[]
     */
    protected array $marketplaceProviderCommercialExtensionActivationServices = [];

    public function addMarketplaceProviderCommercialExtensionActivationService(
        MarketplaceProviderCommercialExtensionActivationServiceInterface $marketplaceProviderCommercialExtensionActivationService
    ): void {
        $this->marketplaceProviderCommercialExtensionActivationServices[] = $marketplaceProviderCommercialExtensionActivationService;
    }

    /**
     * @return MarketplaceProviderCommercialExtensionActivationServiceInterface[]
     */
    public function getMarketplaceProviderCommercialExtensionActivationServices(): array
    {
        $marketplaceProviderCommercialExtensionActivationServices = $this->marketplaceProviderCommercialExtensionActivationServices;
        usort(
            $marketplaceProviderCommercialExtensionActivationServices,
            static fn (
                MarketplaceProviderCommercialExtensionActivationServiceInterface $a,
                MarketplaceProviderCommercialExtensionActivationServiceInterface $b
            ): int => $a->getPriority() <=> $b->getPriority()
        );
        return $marketplaceProviderCommercialExtensionActivationServices;
    }

    public function getMarketplaceProviderCommercialExtensionActivationServiceForLicense(
        string $licenseKey
    ): MarketplaceProviderCommercialExtensionActivationServiceInterface {
        $services = $this->getMarketplaceProviderCommercialExtensionActivationServices();

        foreach ($services as $service) {
            if ($service->canProcessLicense($licenseKey)) {
                return $service;
            }
        }

        throw new RuntimeException(sprintf(
            'No MarketplaceProviderCommercialExtensionActivationService is registered to process license "%s"',
            $licenseKey
        ));
    }
}
