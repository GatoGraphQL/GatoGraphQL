<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Marketplace;

use stdClass;

interface MarketplaceProviderCommercialExtensionActivationServiceInterface
{
    public function activate(string $licenseKey): stdClass;
    public function deactivate(string $licenseKey): stdClass;
}
