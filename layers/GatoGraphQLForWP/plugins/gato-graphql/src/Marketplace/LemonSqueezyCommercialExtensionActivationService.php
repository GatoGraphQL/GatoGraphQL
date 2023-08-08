<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Marketplace;

use stdClass;

class LemonSqueezyCommercialExtensionActivationService implements MarketplaceProviderCommercialExtensionActivationServiceInterface
{
    public function activateLicense(string $licenseKey): stdClass
    {
        $payload = [];
        return (object) $payload;
    }

    public function deactivateLicense(string $licenseKey): stdClass
    {
        $payload = [];
        return (object) $payload;
    }
}
