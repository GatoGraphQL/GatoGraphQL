<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Marketplace;

use GatoGraphQL\GatoGraphQL\MarketplaceProviders\MarketplaceProviderCommercialPluginUpdaterServiceInterface;

interface MarketplaceProviderManagerInterface
{
    public function getMarketplaceProviderFromLicenseKey(string $licenseKey): MarketplaceProviderCommercialPluginUpdaterServiceInterface;
}
