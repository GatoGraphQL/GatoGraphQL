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
     * @param array<string,mixed> Payload stored in the DB from when calling the activation endpoint
     * @return array<string,mixed> Response payload from calling the endpoint
     */
    public function deactivateLicense(
        string $licenseKey,
        array $activatedCommercialExtensionLicensePayload
    ): array;

    /**
     * @param array<string,mixed> Payload stored in the DB from when calling the activation endpoint
     * @return array<string,mixed> Response payload from calling the endpoint
     */
    public function validateLicense(
        string $licenseKey,
        ?array $activatedCommercialExtensionLicensePayload
    ): array;
}
