<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\StaticHelpers;

use GatoGraphQL\GatoGraphQL\Marketplace\Constants\LicenseProperties;
use GatoGraphQL\GatoGraphQL\Marketplace\ObjectModels\CommercialExtensionActivatedLicenseObjectProperties;
use GatoGraphQL\GatoGraphQL\Settings\OptionNamespacerInterface;
use GatoGraphQL\GatoGraphQL\Settings\Options;
use PoP\Root\Facades\Instances\InstanceManagerFacade;

class SettingsHelpers
{
    /**
     * @var array<string,CommercialExtensionActivatedLicenseObjectProperties>|null Extension Slug => License Data
     */
    private static ?array $commercialExtensionActivatedLicenseObjectProperties = null;

    /**
     * Retrieve the license properties for all activated extensions
     *
     * @return array<string,CommercialExtensionActivatedLicenseObjectProperties> Extension Slug => License Data
     */
    public static function getCommercialExtensionActivatedLicenseObjectProperties(): array
    {
        if (self::$commercialExtensionActivatedLicenseObjectProperties === null) {
            $instanceManager = InstanceManagerFacade::getInstance();

            /** @var OptionNamespacerInterface */
            $optionNamespacer = $instanceManager->getInstance(OptionNamespacerInterface::class);
            $option = $optionNamespacer->namespaceOption(Options::COMMERCIAL_EXTENSION_ACTIVATED_LICENSE_ENTRIES);

            /** @var array<string,mixed> */
            $commercialExtensionActivatedLicenseEntries = get_option($option, []);
            self::$commercialExtensionActivatedLicenseObjectProperties = [];
            foreach ($commercialExtensionActivatedLicenseEntries as $extensionSlug => $commercialExtensionActivatedLicenseEntry) {
                self::$commercialExtensionActivatedLicenseObjectProperties[$extensionSlug] = new CommercialExtensionActivatedLicenseObjectProperties(
                    $commercialExtensionActivatedLicenseEntry[LicenseProperties::LICENSE_KEY],
                    $commercialExtensionActivatedLicenseEntry[LicenseProperties::API_RESPONSE_PAYLOAD],
                    $commercialExtensionActivatedLicenseEntry[LicenseProperties::STATUS],
                    $commercialExtensionActivatedLicenseEntry[LicenseProperties::INSTANCE_ID],
                    $commercialExtensionActivatedLicenseEntry[LicenseProperties::INSTANCE_NAME],
                    $commercialExtensionActivatedLicenseEntry[LicenseProperties::ACTIVATION_USAGE],
                    $commercialExtensionActivatedLicenseEntry[LicenseProperties::ACTIVATION_LIMIT],
                    $commercialExtensionActivatedLicenseEntry[LicenseProperties::PRODUCT_NAME],
                    $commercialExtensionActivatedLicenseEntry[LicenseProperties::CUSTOMER_NAME],
                    $commercialExtensionActivatedLicenseEntry[LicenseProperties::CUSTOMER_EMAIL],
                );
            }
        }
        return self::$commercialExtensionActivatedLicenseObjectProperties;
    }
}
