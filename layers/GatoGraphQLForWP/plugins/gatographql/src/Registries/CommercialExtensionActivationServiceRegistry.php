<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Registries;

use GatoGraphQL\GatoGraphQL\Marketplace\MarketplaceProviderCommercialExtensionActivationServiceInterface;
use RuntimeException;

use function sprintf;
use function usort;

class CommercialExtensionActivationServiceRegistry implements CommercialExtensionActivationServiceRegistryInterface
{
    /**
     * @var array<string,MarketplaceProviderCommercialExtensionActivationServiceInterface>
     */
    protected array $marketplaceProviderCommercialExtensionActivationServices = [];

    public function addCommercialExtensionActivationService(
        MarketplaceProviderCommercialExtensionActivationServiceInterface $marketplaceProviderCommercialExtensionActivationService
    ): void {
        $this->marketplaceProviderCommercialExtensionActivationServices[] = $marketplaceProviderCommercialExtensionActivationService;
    }

    /**
     * @return MarketplaceProviderCommercialExtensionActivationServiceInterface[]
     */
    public function getCommercialExtensionActivationServices(): array
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

    public function getCommercialExtensionActivationServiceForLicense(
        string $licenseKey
    ): MarketplaceProviderCommercialExtensionActivationServiceInterface {
        $services = $this->getCommercialExtensionActivationServices();

        foreach ($services as $service) {
            if ($service->canProcessLicense($licenseKey)) {
                return $service;
            }
        }

        throw new RuntimeException(sprintf(
            'No CommercialExtensionActivationService is registered to process license "%s"',
            $licenseKey
        ));
    }
}

