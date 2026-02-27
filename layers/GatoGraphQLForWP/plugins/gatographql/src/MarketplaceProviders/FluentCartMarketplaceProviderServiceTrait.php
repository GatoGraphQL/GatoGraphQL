<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\MarketplaceProviders;

use GatoGraphQL\GatoGraphQL\Marketplace\Constants\LicensePrefixes;

trait FluentCartMarketplaceProviderServiceTrait
{
    public function getPriority(): int
    {
        return 100;
    }

    public function canProcessLicense(string $licenseKey): bool
    {
        return str_starts_with($licenseKey, LicensePrefixes::GATOV2_FLUENTCART);
    }
}
