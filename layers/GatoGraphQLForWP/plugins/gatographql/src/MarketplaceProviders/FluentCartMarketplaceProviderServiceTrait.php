<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\MarketplaceProviders;

use GatoGraphQL\GatoGraphQL\Marketplace\Constants\MarketplaceVersion;
use GatoGraphQL\GatoGraphQL\Marketplace\Constants\MarketplaceLicensePrefixes;

trait FluentCartMarketplaceProviderServiceTrait
{
    public function getPriority(): int
    {
        return 100;
    }

    public function canProcessLicense(string $licenseKey): bool
    {
        return str_starts_with($licenseKey, MarketplaceLicensePrefixes::V2_FLUENTCART);
    }

    public function getMarketplaceVersion(): string
    {
        return MarketplaceVersion::V2_FLUENTCART;
    }
}
