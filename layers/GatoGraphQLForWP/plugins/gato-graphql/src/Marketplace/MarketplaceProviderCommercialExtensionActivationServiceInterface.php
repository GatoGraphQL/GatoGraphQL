<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Marketplace;

interface MarketplaceProviderCommercialExtensionActivationServiceInterface
{
    /**
     * @return array<string,mixed> Response payload from calling the endpoint
     */
    public function activateLicense(string $licenseKey): array;

    /**
     * @return array<string,mixed> Response payload from calling the endpoint
     */
    public function deactivateLicense(string $licenseKey): array;
}
