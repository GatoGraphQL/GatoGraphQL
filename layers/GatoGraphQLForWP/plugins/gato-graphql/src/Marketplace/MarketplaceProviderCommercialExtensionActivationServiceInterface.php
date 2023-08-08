<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Marketplace;

use stdClass;

interface MarketplaceProviderCommercialExtensionActivationServiceInterface
{
    public function activateLicense(string $licenseKey): stdClass;
    public function deactivateLicense(string $licenseKey): stdClass;
}
