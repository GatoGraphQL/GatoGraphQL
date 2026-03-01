<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\MarketplaceProviders;

use GatoGraphQL\GatoGraphQL\Marketplace\Exception\HTTPRequestNotSuccessfulException;
use GatoGraphQL\GatoGraphQL\Marketplace\Exception\LicenseOperationNotSuccessfulException;
use GatoGraphQL\GatoGraphQL\Marketplace\ObjectModels\CommercialExtensionActivatedLicenseObjectProperties;
use GatoGraphQL\GatoGraphQL\ObjectModels\ActiveLicenseCommercialExtensionData;

interface MarketplaceProviderCommercialExtensionActivationServiceInterface
{
    public function getPriority(): int;

    public function canProcessLicense(string $licenseKey): bool;

    public function getMarketplaceVersion(): string;

    /**
     * Check if the instance name from the API response
     * matches the current site.
     */
    public function isInstanceNameValid(string $instanceName): bool;

    /**
     * @throws HTTPRequestNotSuccessfulException If the connection to the Marketplace Provider API failed
     * @throws LicenseOperationNotSuccessfulException If the Marketplace Provider API produced an error for the provided data
     */
    public function activateLicense(
        ?ActiveLicenseCommercialExtensionData $extensionData,
        string $licenseKey,
    ): CommercialExtensionActivatedLicenseObjectProperties;

    /**
     * @throws HTTPRequestNotSuccessfulException If the connection to the Marketplace Provider API failed
     * @throws LicenseOperationNotSuccessfulException If the Marketplace Provider API produced an error for the provided data
     */
    public function deactivateLicense(
        ?ActiveLicenseCommercialExtensionData $extensionData,
        string $licenseKey,
        string $instanceID,
    ): CommercialExtensionActivatedLicenseObjectProperties;

    /**
     * @throws HTTPRequestNotSuccessfulException If the connection to the Marketplace Provider API failed
     * @throws LicenseOperationNotSuccessfulException If the Marketplace Provider API produced an error for the provided data
     */
    public function validateLicense(
        ?ActiveLicenseCommercialExtensionData $extensionData,
        string $licenseKey,
        string $instanceID,
    ): CommercialExtensionActivatedLicenseObjectProperties;
}
