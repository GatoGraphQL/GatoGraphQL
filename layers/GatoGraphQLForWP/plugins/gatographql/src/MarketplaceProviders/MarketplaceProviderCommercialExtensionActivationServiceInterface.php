<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\MarketplaceProviders;

use GatoGraphQL\GatoGraphQL\Marketplace\Exception\HTTPRequestNotSuccessfulException;
use GatoGraphQL\GatoGraphQL\Marketplace\Exception\LicenseOperationNotSuccessfulException;
use GatoGraphQL\GatoGraphQL\Marketplace\ObjectModels\CommercialExtensionActivatedLicenseObjectProperties;

interface MarketplaceProviderCommercialExtensionActivationServiceInterface
{
    public function getPriority(): int;

    public function canProcessLicense(string $licenseKey): bool;

    public function getMarketplaceVersion(): string;

    /**
     * @throws HTTPRequestNotSuccessfulException If the connection to the Marketplace Provider API failed
     * @throws LicenseOperationNotSuccessfulException If the Marketplace Provider API produced an error for the provided data
     */
    public function activateLicense(
        string|int|null $marketplaceProductID,
        string $licenseKey,
        string $instanceName,
    ): CommercialExtensionActivatedLicenseObjectProperties;

    /**
     * @throws HTTPRequestNotSuccessfulException If the connection to the Marketplace Provider API failed
     * @throws LicenseOperationNotSuccessfulException If the Marketplace Provider API produced an error for the provided data
     */
    public function deactivateLicense(
        string|int|null $marketplaceProductID,
        string $licenseKey,
        string $instanceID,
    ): CommercialExtensionActivatedLicenseObjectProperties;

    /**
     * @throws HTTPRequestNotSuccessfulException If the connection to the Marketplace Provider API failed
     * @throws LicenseOperationNotSuccessfulException If the Marketplace Provider API produced an error for the provided data
     */
    public function validateLicense(
        string|int|null $marketplaceProductID,
        string $licenseKey,
        string $instanceID,
    ): CommercialExtensionActivatedLicenseObjectProperties;
}
