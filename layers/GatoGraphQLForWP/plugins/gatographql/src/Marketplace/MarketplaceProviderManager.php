<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Marketplace;

use GatoGraphQL\GatoGraphQL\MarketplaceProviders\MarketplaceProviderCommercialPluginUpdaterServiceInterface;
use GatoGraphQL\GatoGraphQL\Registries\MarketplaceProviderCommercialPluginUpdaterServiceRegistryInterface;
use PoP\Root\Services\AbstractBasicService;
use RuntimeException;

use function sprintf;

class MarketplaceProviderManager extends AbstractBasicService implements MarketplaceProviderManagerInterface
{
    private ?MarketplaceProviderCommercialPluginUpdaterServiceRegistryInterface $marketplaceProviderCommercialPluginUpdaterServiceRegistry = null;

    final protected function getMarketplaceProviderCommercialPluginUpdaterServiceRegistry(): MarketplaceProviderCommercialPluginUpdaterServiceRegistryInterface
    {
        if ($this->marketplaceProviderCommercialPluginUpdaterServiceRegistry === null) {
            /** @var MarketplaceProviderCommercialPluginUpdaterServiceRegistryInterface $registry */
            $registry = $this->instanceManager->getInstance(MarketplaceProviderCommercialPluginUpdaterServiceRegistryInterface::class);
            $this->marketplaceProviderCommercialPluginUpdaterServiceRegistry = $registry;
        }
        return $this->marketplaceProviderCommercialPluginUpdaterServiceRegistry;
    }

    public function getMarketplaceProviderFromLicenseKey(string $licenseKey): MarketplaceProviderCommercialPluginUpdaterServiceInterface
    {
        $services = $this->getMarketplaceProviderCommercialPluginUpdaterServiceRegistry()->getMarketplaceProviderCommercialPluginUpdaterServices();

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
