<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\MarketplaceProviders;

trait LemonSqueezyMarketplaceProviderServiceTrait
{
    public function getPriority(): int
    {
        return 10;
    }

    /**
     * LemonSqueezy is the legacy marketplace provider,
     * so if no other provider can process the license,
     * then we can process it.
     */
    public function canProcessLicense(string $licenseKey): bool
    {
        return true;
    }
}

