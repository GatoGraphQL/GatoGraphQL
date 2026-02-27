<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Marketplace;

interface MarketplaceProviderManagerInterface
{
    public function getMarketplaceProviderFromLicenseKey(string $licenseKey): string;
}

