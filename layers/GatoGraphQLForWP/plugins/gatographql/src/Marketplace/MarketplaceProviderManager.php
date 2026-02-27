<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Marketplace;

use GatoGraphQL\GatoGraphQL\Marketplace\Enums\MarketplaceVersion;
use GatoGraphQL\GatoGraphQL\Marketplace\Constants\LicensePrefixes;
use PoP\Root\Services\AbstractBasicService;

class MarketplaceProviderManager extends AbstractBasicService implements MarketplaceProviderManagerInterface
{
    public function getMarketplaceProviderFromLicenseKey(string $licenseKey): MarketplaceVersion
    {
        if (str_starts_with($licenseKey, LicensePrefixes::GATOV2_FLUENTCART)) {
            return MarketplaceVersion::V2_FluentCart;
        }

        return MarketplaceVersion::V1_LemonSqueezy;
    }
}

