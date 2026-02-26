<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Registries;

use GatoGraphQL\GatoGraphQL\Marketplace\MarketplaceProviderCommercialPluginUpdaterServiceInterface;
use RuntimeException;

use function sprintf;
use function usort;

class MarketplaceProviderCommercialPluginUpdaterServiceRegistry implements MarketplaceProviderCommercialPluginUpdaterServiceRegistryInterface
{
    /**
     * @var MarketplaceProviderCommercialPluginUpdaterServiceInterface[]
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
    public function getMarketplaceProviderCommercialPluginUpdaterServices(): array
    {
        $services = $this->marketplaceProviderCommercialPluginUpdaterServices;
        usort(
            $services,
            static fn (
                MarketplaceProviderCommercialPluginUpdaterServiceInterface $a,
                MarketplaceProviderCommercialPluginUpdaterServiceInterface $b
            ): int => $a->getPriority() <=> $b->getPriority()
        );
        return $services;
    }

    public function getMarketplaceProviderCommercialPluginUpdaterServiceForLicense(
        string $licenseKey
    ): MarketplaceProviderCommercialPluginUpdaterServiceInterface {
        $services = $this->getMarketplaceProviderCommercialPluginUpdaterServices();

        foreach ($services as $service) {
            if ($service->canProcessLicense($licenseKey)) {
                return $service;
            }
        }

        throw new RuntimeException(sprintf(
            'No MarketplaceProviderCommercialPluginUpdaterService is registered to process license "%s"',
            $licenseKey
        ));
    }
}
