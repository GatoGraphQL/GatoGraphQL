<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Marketplace;

use GatoGraphQL\GatoGraphQL\Marketplace\Exception\HTTPRequestNotSuccessfulException;
use GatoGraphQL\GatoGraphQL\Marketplace\Exception\LicenseOperationNotSuccessfulException;
use GatoGraphQL\GatoGraphQL\Marketplace\ObjectModels\CommercialExtensionActivatedLicenseEntryProperties;

interface MarketplaceProviderCommercialExtensionActivationServiceInterface
{
    /**
     * @throws HTTPRequestNotSuccessfulException If the connection to the Marketplace Provider API failed
     * @throws LicenseOperationNotSuccessfulException If the Marketplace Provider API produced an error for the provided data
     */
    public function activateLicense(
        string $licenseKey,
        string $instanceName
    ): CommercialExtensionActivatedLicenseEntryProperties;

    /**
     * @throws HTTPRequestNotSuccessfulException If the connection to the Marketplace Provider API failed
     * @throws LicenseOperationNotSuccessfulException If the Marketplace Provider API produced an error for the provided data
     */
    public function deactivateLicense(
        string $licenseKey,
        string $instanceID
    ): CommercialExtensionActivatedLicenseEntryProperties;

    /**
     * @throws HTTPRequestNotSuccessfulException If the connection to the Marketplace Provider API failed
     * @throws LicenseOperationNotSuccessfulException If the Marketplace Provider API produced an error for the provided data
     */
    public function validateLicense(
        string $licenseKey,
        string $instanceID
    ): CommercialExtensionActivatedLicenseEntryProperties;
}
