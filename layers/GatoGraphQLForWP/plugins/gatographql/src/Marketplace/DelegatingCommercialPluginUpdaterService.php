<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Marketplace;

use GatoGraphQL\GatoGraphQL\Registries\MarketplaceProviderCommercialPluginUpdaterServiceRegistryInterface;
use PoP\Root\Exception\ShouldNotHappenException;
use PoP\Root\Services\AbstractBasicService;

/**
 * Delegates setup to the appropriate provider per license key:
 * groups extension slug => license key by provider, then calls
 * setupMarketplacePluginUpdaterForExtensions on each provider with its subset.
 */
class DelegatingCommercialPluginUpdaterService extends AbstractBasicService implements DelegatingCommercialPluginUpdaterServiceInterface
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

    /**
     * @param array<string,string> $licenseKeys Key: Extension Slug, Value: License Key
     *
     * @throws ShouldNotHappenException If initializing the service more than once
     */
    public function setupMarketplacePluginUpdaterForExtensions(
        array $licenseKeys,
    ): void {
        if ($licenseKeys === []) {
            return;
        }

        $marketplaceProviderCommercialPluginUpdaterServiceRegistry = $this->getMarketplaceProviderCommercialPluginUpdaterServiceRegistry();

        $byProvider = [];
        foreach ($licenseKeys as $extensionSlug => $licenseKey) {
            $provider = $marketplaceProviderCommercialPluginUpdaterServiceRegistry->getMarketplaceProviderCommercialPluginUpdaterServiceForLicense($licenseKey);
            $key = $provider::class;
            if (!isset($byProvider[$key])) {
                $byProvider[$key] = ['provider' => $provider, 'licenseKeys' => []];
            }
            $byProvider[$key]['licenseKeys'][$extensionSlug] = $licenseKey;
        }

        foreach ($byProvider as ['provider' => $provider, 'licenseKeys' => $subset]) {
            $provider->setupMarketplacePluginUpdaterForExtensions($subset);
        }
    }
}
