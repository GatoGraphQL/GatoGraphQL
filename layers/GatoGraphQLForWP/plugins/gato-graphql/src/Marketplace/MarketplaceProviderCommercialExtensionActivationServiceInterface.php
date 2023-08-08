<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Marketplace;

interface MarketplaceProviderCommercialExtensionActivationServiceInterface
{
    public function activate(string $licenseKey): void;
    public function deactivate(string $licenseKey): void;
}
