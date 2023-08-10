<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\StaticHelpers;

use GatoGraphQL\GatoGraphQL\Marketplace\Constants\LicenseProperties;
use GatoGraphQL\GatoGraphQL\Marketplace\ObjectModels\LicenseOperationAPIResponseProperties;
use GatoGraphQL\GatoGraphQL\Settings\Options;

class SettingsHelpers
{
    /**
     * @var LicenseOperationAPIResponseProperties[]|null
     */
    private static ?array $licenseOperationAPIResponseProperties = null;

    /**
     * Retrieve the license properties for all activated extensions
     *
     * @return LicenseOperationAPIResponseProperties[]
     */
    public static function getCommercialExtensionActivatedLicenseEntries(): array
    {
        if (self::$licenseOperationAPIResponseProperties === null) {
            $commercialExtensionActivatedLicenseEntries = get_option(Options::COMMERCIAL_EXTENSION_ACTIVATED_LICENSE_ENTRIES, []);
            self::$licenseOperationAPIResponseProperties = array_map(
                fn (array $commercialExtensionActivatedLicenseEntry) => new LicenseOperationAPIResponseProperties(
                    $commercialExtensionActivatedLicenseEntry[LicenseProperties::LICENSE_KEY],
                    $commercialExtensionActivatedLicenseEntry[LicenseProperties::API_RESPONSE_PAYLOAD],
                    $commercialExtensionActivatedLicenseEntry[LicenseProperties::STATUS],
                    $commercialExtensionActivatedLicenseEntry[LicenseProperties::INSTANCE_ID],
                    $commercialExtensionActivatedLicenseEntry[LicenseProperties::INSTANCE_NAME],
                    $commercialExtensionActivatedLicenseEntry[LicenseProperties::PRODUCT_NAME],
                    $commercialExtensionActivatedLicenseEntry[LicenseProperties::CUSTOMER_NAME],
                    $commercialExtensionActivatedLicenseEntry[LicenseProperties::CUSTOMER_EMAIL],
                ),
                $commercialExtensionActivatedLicenseEntries
            );
        }
        return self::$licenseOperationAPIResponseProperties;
    }
}
