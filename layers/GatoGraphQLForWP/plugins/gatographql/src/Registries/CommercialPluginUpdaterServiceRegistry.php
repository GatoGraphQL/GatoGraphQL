<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Registries;

use GatoGraphQL\GatoGraphQL\Marketplace\MarketplaceProviderCommercialPluginUpdaterServiceInterface;
use RuntimeException;

use function sprintf;
use function usort;

class CommercialPluginUpdaterServiceRegistry implements CommercialPluginUpdaterServiceRegistryInterface
{
    /**
     * @var MarketplaceProviderCommercialPluginUpdaterServiceInterface[]
     */
    protected array $marketplaceProviderCommercialPluginUpdaterServices = [];

    public function addCommercialPluginUpdaterService(
        MarketplaceProviderCommercialPluginUpdaterServiceInterface $marketplaceProviderCommercialPluginUpdaterService
    ): void {
        $this->marketplaceProviderCommercialPluginUpdaterServices[] = $marketplaceProviderCommercialPluginUpdaterService;
    }

    /**
     * @return MarketplaceProviderCommercialPluginUpdaterServiceInterface[]
     */
    public function getCommercialPluginUpdaterServices(): array
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

    public function getCommercialPluginUpdaterServiceForLicense(
        string $licenseKey
    ): MarketplaceProviderCommercialPluginUpdaterServiceInterface {
        $services = $this->getCommercialPluginUpdaterServices();

        foreach ($services as $service) {
            if ($service->canProcessLicense($licenseKey)) {
                return $service;
            }
        }

        throw new RuntimeException(sprintf(
            'No CommercialPluginUpdaterService is registered to process license "%s"',
            $licenseKey
        ));
    }
}
