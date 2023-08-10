<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\StaticHelpers;

use GatoGraphQL\GatoGraphQL\Marketplace\Constants\LicenseProperties;
use GatoGraphQL\GatoGraphQL\Marketplace\ObjectModels\CommercialExtensionActivatedLicenseEntryProperties;
use GatoGraphQL\GatoGraphQL\Settings\Options;

class SettingsHelpers
{
    /**
     * @var array<string,CommercialExtensionActivatedLicenseEntryProperties>|null Extension Slug => License Data
     */
    private static ?array $commercialExtensionActivatedLicenseEntryProperties = null;

    /**
     * Retrieve the license properties for all activated extensions
     *
     * @return array<string,CommercialExtensionActivatedLicenseEntryProperties> Extension Slug => License Data
     */
    public static function getCommercialExtensionActivatedLicenseEntryProperties(): array
    {
        if (self::$commercialExtensionActivatedLicenseEntryProperties === null) {
            $commercialExtensionActivatedLicenseEntries = get_option(Options::COMMERCIAL_EXTENSION_ACTIVATED_LICENSE_ENTRIES, []);
            self::$commercialExtensionActivatedLicenseEntryProperties = [];
            foreach ($commercialExtensionActivatedLicenseEntries as $extensionSlug => $commercialExtensionActivatedLicenseEntry) {
                self::$commercialExtensionActivatedLicenseEntryProperties[$extensionSlug] = new CommercialExtensionActivatedLicenseEntryProperties(
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
        return self::$commercialExtensionActivatedLicenseEntryProperties;
    }
}
