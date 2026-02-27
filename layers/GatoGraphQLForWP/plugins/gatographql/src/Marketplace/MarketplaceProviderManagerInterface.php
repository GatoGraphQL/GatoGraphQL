<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Marketplace;

use GatoGraphQL\GatoGraphQL\Marketplace\Enums\MarketplaceVersion;

interface MarketplaceProviderManagerInterface
{
    public function getMarketplaceProviderFromLicenseKey(string $licenseKey): MarketplaceVersion;
}

