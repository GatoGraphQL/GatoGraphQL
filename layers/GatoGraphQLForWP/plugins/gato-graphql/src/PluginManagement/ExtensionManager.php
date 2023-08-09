<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\PluginManagement;

use GatoGraphQL\ExternalDependencyWrappers\Composer\Semver\SemverWrapper;
use GatoGraphQL\GatoGraphQL\Exception\ExtensionNotRegisteredException;
use GatoGraphQL\GatoGraphQL\Marketplace\Constants\LicenseProperties;
use GatoGraphQL\GatoGraphQL\Marketplace\Constants\LicenseStatus;
use GatoGraphQL\GatoGraphQL\PluginApp;
use GatoGraphQL\GatoGraphQL\PluginSkeleton\BundleExtensionInterface;
use GatoGraphQL\GatoGraphQL\PluginSkeleton\ExtensionInterface;
use GatoGraphQL\GatoGraphQL\Settings\Options;

use function get_option;

class ExtensionManager extends AbstractPluginManager
{
    /** @var string[] */
    private array $inactiveExtensionDependedUponPluginFiles = [];

    /** @var array<string,BundleExtensionInterface> */
    private array $bundledExtensionClassBundlingExtensionClasses = [];

    /** @var array<string,string> Extension Slug => Extension Name */
    private array $nonActivatedLicenseCommercialExtensions = [];

    /** @var array<string,string> Extension Slug => Extension Name */
    private array $activatedLicenseCommercialExtensions = [];

    /** @var array<string,string>|null Extension Slug => Extension Name */
    private ?array $commercialExtensionSlugNames = null;

    /**
     * Have the extensions organized by their class
     *
     * @var array<class-string<ExtensionInterface>,ExtensionInterface>
     */
    private array $extensionClassInstances = [];

    /**
     * Have the extensions organized by their baseName
     *
     * @var array<string,ExtensionInterface>
     */
    private array $extensionBaseNameInstances = [];

    /**
     * License data for all bundles/extensions that have been activated
     *
     * @var array<string,array<string,mixed>>|null Extension Slug => Activated License data item
     */
    private ?array $commercialExtensionActivatedLicenseEntries = null;

    /**
     * Extensions, organized under their name.
     *
     * @return array<string,ExtensionInterface>
     */
    public function getExtensions(): array
    {
        return $this->extensionBaseNameInstances;
    }

    /**
     * @param class-string<ExtensionInterface> $extensionClass
     */
    public function getExtension(string $extensionClass): ExtensionInterface
    {
        if (!isset($this->extensionClassInstances[$extensionClass])) {
            throw new ExtensionNotRegisteredException(
                sprintf(
                    \__('The extension with class \'%s\' has not been registered yet', 'gato-graphql'),
                    $extensionClass
                )
            );
        }
        return $this->extensionClassInstances[$extensionClass];
    }

    public function register(ExtensionInterface $extension): ExtensionInterface
    {
        $extensionClass = get_class($extension);
        $this->extensionClassInstances[$extensionClass] = $extension;
        $this->extensionBaseNameInstances[$extension->getPluginBaseName()] = $extension;
        return $extension;
    }

    public function registerBundle(BundleExtensionInterface $bundleExtension): ExtensionInterface
    {
        $extension = $this->register($bundleExtension);

        /**
         * Register the bundled Extensions:
         *
         *   We must indicate that all the contained Extensions are in a Bundle,
         *   as to let them decide if to enable some functionality or not
         *   (eg: show an error if a required 3rd-party plugin is not active,
         *   or enable a module or not.)
         */
        $bundledExtensionClasses = $bundleExtension->getBundledExtensionClasses();
        foreach ($bundledExtensionClasses as $bundledExtensionClass) {
            $this->bundledExtensionClassBundlingExtensionClasses[$bundledExtensionClass] = $bundleExtension;
        }

        return $extension;
    }

    /**
     * Validate that the extension can be registered:
     *
     * 1. It hasn't been registered yet (eg: the plugin is not duplicated)
     * 2. The required version of the main plugin is the one installed
     *
     * If the assertion fails, it prints an error on the WP admin and returns false
     *
     * @param string|null $mainPluginVersionConstraint the semver version constraint required for the plugin (eg: "^1.0" means >=1.0.0 and <2.0.0)
     * @return bool `true` if the extension can be registered, `false` otherwise
     *
     * @see https://getcomposer.org/doc/articles/versions.md#versions-and-constraints
     */
    public function assertIsValid(
        string $extensionClass,
        string $extensionVersion,
        ?string $extensionName = null,
        ?string $mainPluginVersionConstraint = null,
    ): bool {
        // Validate that the extension is not registered yet.
        if (isset($this->extensionClassInstances[$extensionClass])) {
            /**
             * Check if the installed and new versions are the same.
             * In that case, it may be that the Extension and a
             * Bundle containing that same Extension are installed. In that
             * case, just ignore the error message, and do nothing.
             */
            $installedExtensionVersion = $this->extensionClassInstances[$extensionClass]->getPluginVersion();
            $errorMessage = $installedExtensionVersion === $extensionVersion && $this->isExtensionBundled($extensionClass)
                ? sprintf(
                    __('Extension <strong>%s</strong> with version <code>%s</code> is already installed. Are both the extension and a bundle containing the extension being installed? If so, please keep the bundle only.', 'gato-graphql'),
                    $extensionName ?? $this->extensionClassInstances[$extensionClass]->getPluginName(),
                    $extensionVersion,
                )
                : sprintf(
                    __('Extension <strong>%s</strong> is already installed with version <code>%s</code>, so version <code>%s</code> has not been loaded. Please deactivate all versions, remove the older version, and activate again the latest version of the plugin.', 'gato-graphql'),
                    $extensionName ?? $this->extensionClassInstances[$extensionClass]->getPluginName(),
                    $installedExtensionVersion,
                    $extensionVersion,
                );
            $this->printAdminNoticeErrorMessage($errorMessage);
            return false;
        }

        /**
         * Validate that the required version of the Gato GraphQL for WP plugin is installed.
         */
        $mainPlugin = PluginApp::getMainPluginManager()->getPlugin();
        $mainPluginVersion = $mainPlugin->getPluginVersion();
        if (
            $mainPluginVersionConstraint !== null && !SemverWrapper::satisfies(
                $mainPluginVersion,
                $mainPluginVersionConstraint
            )
        ) {
            $this->printAdminNoticeErrorMessage(
                sprintf(
                    __('Extension <strong>%s</strong> requires plugin <strong>%s</strong> to satisfy version constraint <code>%s</code>, but the current version <code>%s</code> does not. The extension has not been loaded.', 'gato-graphql'),
                    $extensionName ?? $extensionClass,
                    $mainPlugin->getPluginName(),
                    $mainPluginVersionConstraint,
                    $mainPlugin->getPluginVersion(),
                )
            );
            return false;
        }

        return true;
    }

    /**
     * Register the depended-upon plugin main file(s), so that
     * when the plugin is activated, the container is regenerated
     *
     * @param string[] $inactiveExtensionDependedUponPluginFiles
     */
    public function registerInactiveExtensionDependedUponPluginFiles(array $inactiveExtensionDependedUponPluginFiles): void
    {
        $this->inactiveExtensionDependedUponPluginFiles = array_merge(
            $this->inactiveExtensionDependedUponPluginFiles,
            $inactiveExtensionDependedUponPluginFiles
        );
    }

    /**
     * Plugin main files that need to be activated in order
     * for some Gato GraphQL extension to become active.
     *
     * @return string[]
     */
    public function getInactiveExtensionsDependedUponPluginFiles(): array
    {
        return $this->inactiveExtensionDependedUponPluginFiles;
    }

    public function isExtensionBundled(string $bundledExtensionClass): bool
    {
        return $this->getBundlingExtensionClass($bundledExtensionClass) !== null;
    }

    public function getBundlingExtensionClass(string $bundledExtensionClass): ?BundleExtensionInterface
    {
        return $this->bundledExtensionClassBundlingExtensionClasses[$bundledExtensionClass] ?? null;
    }

    /**
     * Validate that the license for the commercial extension
     * has been activated.
     * 
     * If it has not, also mark the Extension as "inactivated",
     * to show a message to the admin.
     */
    public function assertCommercialLicenseHasBeenActivated(
        string $extensionSlug,
        string $extensionName,
    ): bool {
        /**
         * Retrieve from the DB which licenses have been activated,
         * and check if this extension is in it
         */
        $commercialExtensionActivatedLicenseEntries = $this->getCommercialExtensionActivatedLicenseEntries();
        if (!isset($commercialExtensionActivatedLicenseEntries[$extensionSlug])) {
            $this->nonActivatedLicenseCommercialExtensions[$extensionSlug] = $extensionName;
            return false;
        }

        /**
         * Check that the license status is valid to use the plugin:
         *
         * - Active: Plugin has been activated, is currently within subscription period
         * - Expired: Subscription has expired, but users can keep using the plugin
         * 
         * @var array<string,mixed>
         */
        $commercialExtensionActivatedLicenseEntry = $commercialExtensionActivatedLicenseEntries[$extensionSlug];
        /** @var string */
        $licenseStatus = $commercialExtensionActivatedLicenseEntry[LicenseProperties::STATUS];
        if (!in_array($licenseStatus, [
            LicenseStatus::ACTIVE,
            LicenseStatus::EXPIRED,
        ])) {
            $this->nonActivatedLicenseCommercialExtensions[$extensionSlug] = $extensionName;
            return false;
        }
        $this->activatedLicenseCommercialExtensions[$extensionSlug] = $extensionName;
        return true;
    }

    /**
     * Retrieve the license data for all bundles/extensions that
     * have been activated.
     *
     * @return array<string,array<string,mixed>> Extension Slug => Activated License data item
     */
    protected function getCommercialExtensionActivatedLicenseEntries(): array
    {
        if ($this->commercialExtensionActivatedLicenseEntries === null) {
            $this->commercialExtensionActivatedLicenseEntries = get_option(Options::COMMERCIAL_EXTENSION_ACTIVATED_LICENSE_ENTRIES, []);
        }
        return $this->commercialExtensionActivatedLicenseEntries;
    }

    /**
     * @return array<string,string> Extension Slug => Extension Name
     */
    public function getNonActivatedLicenseCommercialExtensions(): array
    {
        return $this->nonActivatedLicenseCommercialExtensions;
    }

    /**
     * @return array<string,string> Extension Slug => Extension Name
     */
    public function getActivatedLicenseCommercialExtensions(): array
    {
        return $this->activatedLicenseCommercialExtensions;
    }

    /**
     * @return array<string,string> Extension Slug => Extension Name
     */
    public function getCommercialExtensionSlugNames(): array
    {
        if ($this->commercialExtensionSlugNames === null) {
            $this->commercialExtensionSlugNames = array_merge(
                $this->getNonActivatedLicenseCommercialExtensions(),
                $this->getActivatedLicenseCommercialExtensions(),
            );
            ksort($this->commercialExtensionSlugNames);
        }
        return $this->commercialExtensionSlugNames;
    }
}
