<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\StaticHelpers;

use GatoGraphQL\GatoGraphQL\Marketplace\Constants\LicenseProperties;
use GatoGraphQL\GatoGraphQL\Marketplace\ObjectModels\LicenseOperationAPIResponseProperties;
use GatoGraphQL\GatoGraphQL\Settings\Options;

class SettingsHelpers
{
    /**
     * @var array<string,LicenseOperationAPIResponseProperties>|null Extension Slug => License Data
     */
    private static ?array $licenseOperationAPIResponseProperties = null;

    /**
     * Retrieve the license properties for all activated extensions
     *
     * @return array<string,LicenseOperationAPIResponseProperties> Extension Slug => License Data
     */
    public static function getLicenseOperationAPIResponseProperties(): array
    {
        if (self::$licenseOperationAPIResponseProperties === null) {
            $commercialExtensionActivatedLicenseEntries = get_option(Options::COMMERCIAL_EXTENSION_ACTIVATED_LICENSE_ENTRIES, []);
            self::$licenseOperationAPIResponseProperties = [];
            foreach ($commercialExtensionActivatedLicenseEntries as $extensionSlug => $commercialExtensionActivatedLicenseEntry) {
                self::$licenseOperationAPIResponseProperties[$extensionSlug] = new LicenseOperationAPIResponseProperties(
                    $commercialExtensionActivatedLicenseEntry[LicenseProperties::LICENSE_KEY],
                    $commercialExtensionActivatedLicenseEntry[LicenseProperties::API_RESPONSE_PAYLOAD],
                    $commercialExtensionActivatedLicenseEntry[LicenseProperties::STATUS],
                    $commercialExtensionActivatedLicenseEntry[LicenseProperties::INSTANCE_ID],
                    $commercialExtensionActivatedLicenseEntry[LicenseProperties::INSTANCE_NAME],
                    $commercialExtensionActivatedLicenseEntry[LicenseProperties::PRODUCT_NAME],
                    $commercialExtensionActivatedLicenseEntry[LicenseProperties::ACTIVATION_USAGE],
                    $commercialExtensionActivatedLicenseEntry[LicenseProperties::ACTIVATION_LIMIT],
                    $commercialExtensionActivatedLicenseEntry[LicenseProperties::CUSTOMER_NAME],
                    $commercialExtensionActivatedLicenseEntry[LicenseProperties::CUSTOMER_EMAIL],
                );
            }
        }
        return self::$licenseOperationAPIResponseProperties;
    }
}
