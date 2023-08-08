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
