<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Marketplace;

use GatoGraphQL\GatoGraphQL\Marketplace\Constants\MarketplaceVersion;
use GatoGraphQL\GatoGraphQL\Marketplace\Constants\LicensePrefixes;
use PoP\Root\Services\AbstractBasicService;

class MarketplaceProviderManager extends AbstractBasicService implements MarketplaceProviderManagerInterface
{
    public function getMarketplaceProviderFromLicenseKey(string $licenseKey): string
    {
        if (str_starts_with($licenseKey, LicensePrefixes::GATOV2_FLUENTCART)) {
            return MarketplaceVersion::V2_FLUENTCART;
        }

        return MarketplaceVersion::V1_LEMONSQUEEZY;
    }
}

