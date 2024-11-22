<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Marketplace;

use GatoGraphQL\GatoGraphQL\Container\ContainerManagerInterface;
use GatoGraphQL\GatoGraphQL\Facades\Settings\OptionNamespacerFacade;
use GatoGraphQL\GatoGraphQL\Facades\UserSettingsManagerFacade;
use GatoGraphQL\GatoGraphQL\Marketplace\Constants\DBFlags;
use GatoGraphQL\GatoGraphQL\Marketplace\Constants\LicenseProperties;
use GatoGraphQL\GatoGraphQL\Marketplace\Exception\HTTPRequestNotSuccessfulException;
use GatoGraphQL\GatoGraphQL\Marketplace\Exception\LicenseOperationNotSuccessfulException;
use GatoGraphQL\GatoGraphQL\Marketplace\MarketplaceProviderCommercialExtensionActivationServiceInterface;
use GatoGraphQL\GatoGraphQL\Marketplace\ObjectModels\CommercialExtensionActivatedLicenseObjectProperties;
use GatoGraphQL\GatoGraphQL\PluginApp;
use GatoGraphQL\GatoGraphQL\PluginSkeleton\PluginOptions;
use GatoGraphQL\GatoGraphQL\Settings\OptionNamespacerInterface;
use GatoGraphQL\GatoGraphQL\Settings\Options;
use GatoGraphQL\GatoGraphQL\Settings\UserSettingsManagerInterface;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\Root\Services\BasicServiceTrait;

use function add_settings_error;
use function get_option;
use function home_url;
use function update_option;

class LicenseValidationService implements LicenseValidationServiceInterface
{
    use BasicServiceTrait;

    private ?MarketplaceProviderCommercialExtensionActivationServiceInterface $marketplaceProviderCommercialExtensionActivationService = null;
    private ?ContainerManagerInterface $containerManager = null;
    private ?UserSettingsManagerInterface $userSettingsManager = null;
    private ?OptionNamespacerInterface $optionNamespacer = null;

    final protected function getMarketplaceProviderCommercialExtensionActivationService(): MarketplaceProviderCommercialExtensionActivationServiceInterface
    {
        if ($this->marketplaceProviderCommercialExtensionActivationService === null) {
            /** @var MarketplaceProviderCommercialExtensionActivationServiceInterface */
            $marketplaceProviderCommercialExtensionActivationService = $this->instanceManager->getInstance(MarketplaceProviderCommercialExtensionActivationServiceInterface::class);
            $this->marketplaceProviderCommercialExtensionActivationService = $marketplaceProviderCommercialExtensionActivationService;
        }
        return $this->marketplaceProviderCommercialExtensionActivationService;
    }
    final protected function getContainerManager(): ContainerManagerInterface
    {
        if ($this->containerManager === null) {
            /** @var ContainerManagerInterface */
            $containerManager = $this->instanceManager->getInstance(ContainerManagerInterface::class);
            $this->containerManager = $containerManager;
        }
        return $this->containerManager;
    }
    final protected function getUserSettingsManager(): UserSettingsManagerInterface
    {
        return $this->userSettingsManager ??= UserSettingsManagerFacade::getInstance();
    }
    final protected function getOptionNamespacer(): OptionNamespacerInterface
    {
        return $this->optionNamespacer ??= OptionNamespacerFacade::getInstance();
    }

    /**
     * Activate the Gato GraphQL Extensions against the
     * marketplace provider's API.
     *
     * @param array<string,string> $previousLicenseKeys Key: Extension Slug, Value: License Key
     * @param array<string,string> $submittedLicenseKeys Key: Extension Slug, Value: License Key
     */
    public function activateDeactivateValidateGatoGraphQLCommercialExtensions(
        array $previousLicenseKeys,
        array $submittedLicenseKeys,
        ?string $formSettingName = null,
    ): void {
        // Store the latest "license check" timestamp to DB
        $this->getUserSettingsManager()->storeLicenseCheckTimestamp();

        $optionNamespacer = $this->getOptionNamespacer();
        $option = $optionNamespacer->namespaceOption(Options::COMMERCIAL_EXTENSION_ACTIVATED_LICENSE_ENTRIES);
        /** @var array<string,mixed> */
        $commercialExtensionActivatedLicenseEntries = get_option($option, []);
        [
            $activateLicenseKeys,
            $deactivateLicenseKeys,
            $validateLicenseKeys,
        ] = $this->calculateLicenseKeysToActivateDeactivateValidate(
            $commercialExtensionActivatedLicenseEntries,
            $previousLicenseKeys,
            $submittedLicenseKeys,
        );

        if (
            $activateLicenseKeys === []
            && $deactivateLicenseKeys === []
            && $validateLicenseKeys === []
        ) {
            return;
        }

        // Keep a record of the extensions that have just been activated
        $justActivatedCommercialExtensionSlugs = [];

        // Keep the original values, to only flush the container if these have changed
        $originalCommercialExtensionActivatedLicenseEntries = $commercialExtensionActivatedLicenseEntries;

        $extensionManager = PluginApp::getExtensionManager();
        $commercialExtensionSlugProductNames = $extensionManager->getCommercialExtensionSlugProductNames();
        $marketplaceProviderCommercialExtensionActivationService = $this->getMarketplaceProviderCommercialExtensionActivationService();
        $commercialExtensionActivatedLicenseObjectProperties = null;

        foreach ($validateLicenseKeys as $extensionSlug => $licenseKey) {
            /**
             * If the extensionName is empty, that means that a previously-activated
             * bundle has been deactivated, with the license still in the DB.
             * In that case, do nothing.
             *
             * @var string
             */
            $extensionName = $commercialExtensionSlugProductNames[$extensionSlug] ?? '';
            if ($extensionName === '') {
                continue;
            }
            /** @var array<string,mixed> */
            $commercialExtensionActivatedLicenseEntry = $commercialExtensionActivatedLicenseEntries[$extensionSlug];
            /** @var string */
            $instanceID = $commercialExtensionActivatedLicenseEntry[LicenseProperties::INSTANCE_ID];
            try {
                $commercialExtensionActivatedLicenseObjectProperties = $marketplaceProviderCommercialExtensionActivationService->validateLicense(
                    $licenseKey,
                    $instanceID,
                );
            } catch (HTTPRequestNotSuccessfulException | LicenseOperationNotSuccessfulException $e) {
                $errorMessage = sprintf(
                    \__('Validating license for "%s" produced error: %s', 'gatographql'),
                    $extensionName,
                    $e->getMessage()
                );
                $commercialExtensionActivatedLicenseEntries = $this->handleLicenseOperationError(
                    $commercialExtensionActivatedLicenseEntries,
                    $extensionSlug,
                    $e,
                );
                if ($formSettingName !== null) {
                    $this->showAdminMessagesOnLicenseOperationError(
                        $extensionSlug,
                        $errorMessage,
                        $e,
                        $formSettingName,
                    );
                }
                continue;
            }

            $commercialExtensionActivatedLicenseEntries = $this->addCommercialExtensionActivatedLicenseEntry(
                $commercialExtensionActivatedLicenseEntries,
                $extensionSlug,
                $commercialExtensionActivatedLicenseObjectProperties,
            );

            $successMessage = sprintf(
                \__('The license for "%s" has status "%s". You have %s/%s instances activated.', 'gatographql'),
                $extensionName,
                $commercialExtensionActivatedLicenseObjectProperties->status,
                $commercialExtensionActivatedLicenseObjectProperties->activationUsage,
                $commercialExtensionActivatedLicenseObjectProperties->activationLimit,
            );
            if ($formSettingName !== null) {
                $this->showAdminMessagesOnLicenseOperationSuccess(
                    $extensionSlug,
                    $extensionName,
                    $commercialExtensionActivatedLicenseObjectProperties,
                    $successMessage,
                    $formSettingName,
                );
            }
        }

        /**
         * First deactivate and then activate licenses, because an extension
         * might be deactivated + reactivated (with a different license key)
         */
        foreach ($deactivateLicenseKeys as $extensionSlug => $licenseKey) {
            /**
             * Make sure the bundle is active. If not, do nothing.
             *
             * @var string
             */
            $extensionName = $commercialExtensionSlugProductNames[$extensionSlug] ?? '';
            if ($extensionName === '') {
                continue;
            }
            /** @var array<string,mixed> */
            $commercialExtensionActivatedLicenseEntry = $commercialExtensionActivatedLicenseEntries[$extensionSlug];
            /** @var string */
            $instanceID = $commercialExtensionActivatedLicenseEntry[LicenseProperties::INSTANCE_ID];
            try {
                $commercialExtensionActivatedLicenseObjectProperties = $marketplaceProviderCommercialExtensionActivationService->deactivateLicense(
                    $licenseKey,
                    $instanceID,
                );
            } catch (HTTPRequestNotSuccessfulException | LicenseOperationNotSuccessfulException $e) {
                $errorMessage = sprintf(
                    \__('Deactivating license for "%s" produced error: %s', 'gatographql'),
                    $extensionName,
                    $e->getMessage()
                );
                $commercialExtensionActivatedLicenseEntries = $this->handleLicenseOperationError(
                    $commercialExtensionActivatedLicenseEntries,
                    $extensionSlug,
                    $e,
                );
                if ($formSettingName !== null) {
                    $this->showAdminMessagesOnLicenseOperationError(
                        $extensionSlug,
                        $errorMessage,
                        $e,
                        $formSettingName,
                    );
                }
                continue;
            }

            // Do not store deactivated instances
            unset($commercialExtensionActivatedLicenseEntries[$extensionSlug]);

            $successMessage = sprintf(
                \__('Deactivating license for "%s" succeeded. You now have %s/%s instances activated.', 'gatographql'),
                $extensionName,
                $commercialExtensionActivatedLicenseObjectProperties->activationUsage,
                $commercialExtensionActivatedLicenseObjectProperties->activationLimit,
            );
            if ($formSettingName !== null) {
                $this->showAdminMessagesOnLicenseOperationSuccess(
                    $extensionSlug,
                    $extensionName,
                    $commercialExtensionActivatedLicenseObjectProperties,
                    $successMessage,
                    $formSettingName,
                );
            }
        }

        foreach ($activateLicenseKeys as $extensionSlug => $licenseKey) {
            /**
             * Make sure the bundle is active. If not, do nothing.
             *
             * @var string
             */
            $extensionName = $commercialExtensionSlugProductNames[$extensionSlug] ?? '';
            if ($extensionName === '') {
                continue;
            }
            $instanceName = $this->getInstanceName($extensionSlug);
            try {
                $commercialExtensionActivatedLicenseObjectProperties = $marketplaceProviderCommercialExtensionActivationService->activateLicense($licenseKey, $instanceName);
            } catch (HTTPRequestNotSuccessfulException | LicenseOperationNotSuccessfulException $e) {
                $errorMessage = sprintf(
                    \__('Activating license for "%s" produced error: %s', 'gatographql'),
                    $extensionName,
                    $e->getMessage()
                );
                $commercialExtensionActivatedLicenseEntries = $this->handleLicenseOperationError(
                    $commercialExtensionActivatedLicenseEntries,
                    $extensionSlug,
                    $e,
                );
                if ($formSettingName !== null) {
                    $this->showAdminMessagesOnLicenseOperationError(
                        $extensionSlug,
                        $errorMessage,
                        $e,
                        $formSettingName,
                    );
                }
                continue;
            }

            $commercialExtensionActivatedLicenseEntries = $this->addCommercialExtensionActivatedLicenseEntry(
                $commercialExtensionActivatedLicenseEntries,
                $extensionSlug,
                $commercialExtensionActivatedLicenseObjectProperties,
            );

            $successMessage = sprintf(
                \__('Activating license for "%s" succeeded. You have %s/%s instances activated.', 'gatographql'),
                $extensionName,
                $commercialExtensionActivatedLicenseObjectProperties->activationUsage,
                $commercialExtensionActivatedLicenseObjectProperties->activationLimit,
            );
            $justActivatedCommercialExtensionSlugs[] = $extensionSlug;
            if ($formSettingName !== null) {
                $this->showAdminMessagesOnLicenseOperationSuccess(
                    $extensionSlug,
                    $extensionName,
                    $commercialExtensionActivatedLicenseObjectProperties,
                    $successMessage,
                    $formSettingName,
                );
            }
        }

        // Do not flush container or update DB if there are no changes
        if ($originalCommercialExtensionActivatedLicenseEntries === $commercialExtensionActivatedLicenseEntries) {
            return;
        }

        // Store the payloads to the DB
        update_option(
            $option,
            $commercialExtensionActivatedLicenseEntries
        );

        // Because extensions will be activated/deactivated, flush the service container
        $this->getContainerManager()->flushContainer(true, true);

        if ($justActivatedCommercialExtensionSlugs === []) {
            return;
        }

        /**
         * Actually...
         *
         * Calling `flush_rewrite_rules` when activating the extension's
         * license (in options.php) doesn't work, the CPTs do not load
         * properly afterwards. This must be invoked right after. That's
         * why we use a DBFlag to indicate this state.
         */
        $option = $optionNamespacer->namespaceOption(PluginOptions::PLUGIN_VERSIONS);
        $storedPluginVersions = get_option($option, []);
        $registeredExtensionBaseNameInstances = PluginApp::getExtensionManager()->getExtensions();
        foreach ($registeredExtensionBaseNameInstances as $extensionBaseName => $extensionInstance) {
            if (!in_array($extensionInstance->getPluginSlug(), $justActivatedCommercialExtensionSlugs)) {
                continue;
            }
            $storedPluginVersions[$extensionBaseName] = DBFlags::JUST_ACTIVATED_COMMERCIAL_EXTENSION_LICENSE;
        }
        update_option($option, $storedPluginVersions);
    }

    /**
     * If there was an HTTPRequest error, then do not unset the stored
     * payload in the DB.
     *
     * If there was a LicenseOperation error, that means the instance
     * does not exist, or some other problem, and hence deactivate the
     * instance.
     *
     * @param array<string,mixed> $commercialExtensionActivatedLicenseEntries
     * @return array<string,mixed>
     */
    protected function handleLicenseOperationError(
        array $commercialExtensionActivatedLicenseEntries,
        string $extensionSlug,
        HTTPRequestNotSuccessfulException | LicenseOperationNotSuccessfulException $e,
    ): array {
        if ($e instanceof LicenseOperationNotSuccessfulException) {
            unset($commercialExtensionActivatedLicenseEntries[$extensionSlug]);
        }

        return $commercialExtensionActivatedLicenseEntries;
    }

    protected function showAdminMessagesOnLicenseOperationError(
        string $extensionSlug,
        string $errorMessage,
        HTTPRequestNotSuccessfulException | LicenseOperationNotSuccessfulException $e,
        string $formSettingName,
    ): void {
        /**
         * Show the error message to the admin.
         *
         * Make sure this method is invoked only when
         * this function is loaded, in the Settings page.
         */
        if (!function_exists('add_settings_error')) {
            return;
        }

        $type = $e instanceof LicenseOperationNotSuccessfulException ? 'error' : 'warning';
        add_settings_error(
            $formSettingName,
            'license_activation_' . $extensionSlug,
            $errorMessage,
            $type
        );
    }

    /**
     * Add the payload entry to be stored in the DB.
     *
     * @param array<string,mixed> $commercialExtensionActivatedLicenseEntries
     * @return array<string,mixed>
     */
    protected function addCommercialExtensionActivatedLicenseEntry(
        array $commercialExtensionActivatedLicenseEntries,
        string $extensionSlug,
        CommercialExtensionActivatedLicenseObjectProperties $commercialExtensionActivatedLicenseObjectProperties,
    ): array {
        /** @var string */
        $instanceID = $commercialExtensionActivatedLicenseObjectProperties->instanceID;
        /** @var string */
        $instanceName = $commercialExtensionActivatedLicenseObjectProperties->instanceName;
        $commercialExtensionActivatedLicenseEntries[$extensionSlug] = [
            LicenseProperties::LICENSE_KEY => $commercialExtensionActivatedLicenseObjectProperties->licenseKey,
            LicenseProperties::API_RESPONSE_PAYLOAD => $commercialExtensionActivatedLicenseObjectProperties->apiResponsePayload,
            LicenseProperties::STATUS => $commercialExtensionActivatedLicenseObjectProperties->status,
            LicenseProperties::INSTANCE_ID => $instanceID,
            /**
             * The instance name is generated by the plugin, hence there is would be
             * no need to store it in the DB. However, it is stored as to pre-populate
             * the "Support" form, to help the Gato GraphQL team understand what
             * extensions are being used.
             */
            LicenseProperties::INSTANCE_NAME => $instanceName,
            LicenseProperties::ACTIVATION_USAGE => $commercialExtensionActivatedLicenseObjectProperties->activationUsage,
            LicenseProperties::ACTIVATION_LIMIT => $commercialExtensionActivatedLicenseObjectProperties->activationLimit,
            /**
             * The product name is stored as to validate that the license key
             * provided in the Settings belongs to the right extension.
             *
             * @see `assertCommercialLicenseHasBeenActivated` in class `ExtensionManager`
             */
            LicenseProperties::PRODUCT_NAME => $commercialExtensionActivatedLicenseObjectProperties->productName,
            /**
             * The customer name and email are stored as to pre-populate
             * the "Support" form
             */
            LicenseProperties::CUSTOMER_NAME => $commercialExtensionActivatedLicenseObjectProperties->customerName,
            LicenseProperties::CUSTOMER_EMAIL => $commercialExtensionActivatedLicenseObjectProperties->customerEmail,
        ];

        return $commercialExtensionActivatedLicenseEntries;
    }

    protected function showAdminMessagesOnLicenseOperationSuccess(
        string $extensionSlug,
        string $extensionName,
        CommercialExtensionActivatedLicenseObjectProperties $commercialExtensionActivatedLicenseObjectProperties,
        string $successMessage,
        string $formSettingName,
    ): void {
        /**
         * Make sure this method is invoked only when
         * this function is loaded, in the Settings page.
         */
        if (!function_exists('add_settings_error')) {
            return;
        }

        // Show the success message to the admin
        add_settings_error(
            $formSettingName,
            'license_activation_' . $extensionSlug,
            $successMessage,
            'success'
        );

        if ($commercialExtensionActivatedLicenseObjectProperties->productName === $extensionName) {
            return;
        }

        // Show the error message to the admin
        add_settings_error(
            $formSettingName,
            'license_activation_' . $extensionSlug,
            sprintf(
                \__('The license key provided for "%1$s" is meant to be used with "%2$s". As such, "%1$s" has not been enabled. Please use the right license key to enable it.<br/>If you need help, send an email to support@gatographql.com (providing the purchased license keys).'),
                $extensionName,
                $commercialExtensionActivatedLicenseObjectProperties->productName,
            ),
            'error'
        );
    }

    /**
     * Calculate which extensions must be activated, which must be deactivated,
     * and which must be both.
     *
     * Every entry in $submittedLicenseKeys is compared against $previousLicenseKeys and,
     * depending on both values, either there is nothing to do, or the license is
     * activated/deactivated/both:
     *
     * - If the license key is empty in both, then nothing to do
     * - If the license key has not been updated, then validate it
     * - If the license key is new (i.e. it was empty before), then activate it
     * - If the license key is removed (i.e. it is empty now, non-empty before), then deactivate it
     * - If the license key has been updated, then deactivate the previous one, and activate the new one
     *
     * @param array<string,mixed> $commercialExtensionActivatedLicenseEntries
     * @param array<string,string> $previousLicenseKeys Key: Extension Slug, Value: License Key
     * @param array<string,string> $submittedLicenseKeys Key: Extension Slug, Value: License Key
     * @return array{0:array<string,string>,1:array<string,string>,2:array<string,string>} [0]: $activateLicenseKeys, [1]: $deactivateLicenseKeys, [2]: $validateLicenseKeys], with array items as: Key: Extension Slug, Value: License Key
     */
    protected function calculateLicenseKeysToActivateDeactivateValidate(
        array $commercialExtensionActivatedLicenseEntries,
        array $previousLicenseKeys,
        array $submittedLicenseKeys,
    ): array {
        $deactivateLicenseKeys = [];
        $activateLicenseKeys = [];
        $validateLicenseKeys = [];

        // Iterate all submitted entries to activate extensions
        foreach ($submittedLicenseKeys as $extensionSlug => $submittedLicenseKey) {
            $submittedLicenseKey = trim($submittedLicenseKey);
            if ($submittedLicenseKey === '') {
                // License key not set => Skip
                continue;
            }
            $hasExtensionBeenActivated = isset($commercialExtensionActivatedLicenseEntries[$extensionSlug]);
            $previousLicenseKey = trim($previousLicenseKeys[$extensionSlug] ?? '');
            if ($previousLicenseKey === $submittedLicenseKey) {
                if ($hasExtensionBeenActivated) {
                    // License key not updated => Validate
                    $validateLicenseKeys[$extensionSlug] = $submittedLicenseKey;
                } else {
                    // The previous license had (for some reason) not be activated => Activate
                    $activateLicenseKeys[$extensionSlug] = $submittedLicenseKey;
                }
                continue;
            }
            if ($previousLicenseKey === '') {
                // License key newly added => Activate
                $activateLicenseKeys[$extensionSlug] = $submittedLicenseKey;
                continue;
            }
            // License key updated => Deactivate + Activate
            if ($hasExtensionBeenActivated) {
                $deactivateLicenseKeys[$extensionSlug] = $previousLicenseKey;
            }
            $activateLicenseKeys[$extensionSlug] = $submittedLicenseKey;
        }

        // Iterate all previous entries to deactivate extensions
        foreach ($previousLicenseKeys as $extensionSlug => $previousLicenseKey) {
            $previousLicenseKey = trim($previousLicenseKey);
            if ($previousLicenseKey === '') {
                // License key not previously set => Skip
                continue;
            }
            $submittedLicenseKey = trim($submittedLicenseKeys[$extensionSlug] ?? '');
            if ($previousLicenseKey === $submittedLicenseKey) {
                // License key not updated => Do nothing (Validate: already queued above)
                continue;
            }
            if ($submittedLicenseKey === '') {
                $hasExtensionBeenActivated = isset($commercialExtensionActivatedLicenseEntries[$extensionSlug]);
                if ($hasExtensionBeenActivated) {
                    // License key newly removed => Deactivate
                    $deactivateLicenseKeys[$extensionSlug] = $previousLicenseKey;
                }
                continue;
            }
            // License key updated => Do nothing (Deactivate + Activate: already queued above)
        }

        return [
            $activateLicenseKeys,
            $deactivateLicenseKeys,
            $validateLicenseKeys,
        ];
    }

    /**
     * Use as the instance name:
     *
     * - The site's domain: to understand on what domain it was installed
     * - Extension slug: to make sure the right license key was provided
     */
    protected function getInstanceName(string $extensionSlug): string
    {
        return sprintf(
            '%s (%s)',
            GeneralUtils::getHost(home_url()),
            $extensionSlug
        );
    }

    /**
     * Re-validate the Gato GraphQL Extensions against the
     * marketplace provider's API.
     *
     * @param array<string,string> $activeLicenseKeys Key: Extension Slug, Value: License Key
     */
    public function validateGatoGraphQLCommercialExtensions(
        array $activeLicenseKeys,
        ?string $formSettingName = null,
    ): void {
        $this->activateDeactivateValidateGatoGraphQLCommercialExtensions(
            $activeLicenseKeys,
            $activeLicenseKeys,
            $formSettingName,
        );
    }
}
