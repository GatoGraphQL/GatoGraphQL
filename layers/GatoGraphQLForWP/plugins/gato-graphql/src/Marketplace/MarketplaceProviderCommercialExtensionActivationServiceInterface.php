<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Marketplace;

use GatoGraphQL\GatoGraphQL\Marketplace\ObjectModels\ActivateLicenseAPIResponseProperties;
use GatoGraphQL\GatoGraphQL\Marketplace\ObjectModels\DeactivateLicenseAPIResponseProperties;
use GatoGraphQL\GatoGraphQL\Marketplace\ObjectModels\ValidateLicenseAPIResponseProperties;

interface MarketplaceProviderCommercialExtensionActivationServiceInterface
{
    public function activateLicense(
        string $licenseKey,
        string $instanceName
    ): ActivateLicenseAPIResponseProperties;

    public function deactivateLicense(
        string $licenseKey,
        string $instanceID
    ): DeactivateLicenseAPIResponseProperties;

    public function validateLicense(
        string $licenseKey,
        ?string $instanceID
    ): ValidateLicenseAPIResponseProperties;
}
