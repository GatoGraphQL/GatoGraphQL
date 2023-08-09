<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Marketplace;

use GatoGraphQL\GatoGraphQL\Marketplace\ObjectModels\ActivateLicenseAPIResponseProperties;

interface MarketplaceProviderCommercialExtensionActivationServiceInterface
{
    public function activateLicense(
        string $licenseKey,
        string $instanceName
    ): ActivateLicenseAPIResponseProperties;

    /**
     * @return array<string,mixed> Response payload from calling the endpoint
     */
    public function deactivateLicense(
        string $licenseKey,
        string $instanceID
    ): array;

    /**
     * @return array<string,mixed> Response payload from calling the endpoint
     */
    public function validateLicense(
        string $licenseKey,
        ?string $instanceID
    ): array;
}
