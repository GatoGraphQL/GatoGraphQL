<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Marketplace;

use PoP\Root\Exception\ShouldNotHappenException;
use PoP\Root\Services\AbstractBasicService;

/**
 * Delegates setup to the appropriate provider per license key:
 * groups extension slug => license key by provider, then calls
 * setupMarketplacePluginUpdaterForExtensions on each provider with its subset.
 */
class DelegatingCommercialPluginUpdaterService extends AbstractBasicService implements DelegatingCommercialPluginUpdaterServiceInterface
{
    private ?MarketplaceProviderManagerInterface $marketplaceProviderManager = null;

    final protected function getMarketplaceProviderManager(): MarketplaceProviderManagerInterface
    {
        if ($this->marketplaceProviderManager === null) {
            /** @var MarketplaceProviderManagerInterface $registry */
            $registry = $this->instanceManager->getInstance(MarketplaceProviderManagerInterface::class);
            $this->marketplaceProviderManager = $registry;
        }
        return $this->marketplaceProviderManager;
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

        $marketplaceProviderManager = $this->getMarketplaceProviderManager();

        $byProvider = [];
        foreach ($licenseKeys as $extensionSlug => $licenseKey) {
            $marketplaceProvider = $marketplaceProviderManager->getMarketplaceProviderFromLicenseKey($licenseKey);
            $key = $marketplaceProvider->getMarketplaceVersion();
            if (!isset($byProvider[$key])) {
                $byProvider[$key] = ['marketplaceProvider' => $marketplaceProvider, 'licenseKeys' => []];
            }
            $byProvider[$key]['licenseKeys'][$extensionSlug] = $licenseKey;
        }

        foreach ($byProvider as ['marketplaceProvider' => $marketplaceProvider, 'licenseKeys' => $subset]) {
            $marketplaceProvider->setupMarketplacePluginUpdaterForExtensions($subset);
        }
    }
}
