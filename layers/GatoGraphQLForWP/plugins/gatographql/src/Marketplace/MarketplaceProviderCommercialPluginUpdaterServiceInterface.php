<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Marketplace;

interface MarketplaceProviderCommercialPluginUpdaterServiceInterface
{
    /**
     * Use the Marketplace provider's service to
     * update the active commercial extensions
     *
     * @param array<string,string> $licenseKeys Key: Extension Slug, Value: License Key
     */
    public function useMarketplacePluginUpdaterForExtensions(
        array $licenseKeys,
    ): void;
}
