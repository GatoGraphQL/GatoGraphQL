<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Marketplace;

use GatoGraphQL\GatoGraphQL\Marketplace\ObjectModels\LicenseOperationAPIResponseProperties;

interface MarketplaceProviderCommercialExtensionActivationServiceInterface
{
    public function activateLicense(
        string $licenseKey,
        string $instanceName
    ): LicenseOperationAPIResponseProperties;

    public function deactivateLicense(
        string $licenseKey,
        string $instanceID
    ): LicenseOperationAPIResponseProperties;

    public function validateLicense(
        string $licenseKey,
        string $instanceID
    ): LicenseOperationAPIResponseProperties;
}
