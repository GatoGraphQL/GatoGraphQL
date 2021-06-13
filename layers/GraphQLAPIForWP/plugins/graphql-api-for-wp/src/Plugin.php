<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI;

use Exception;
use GraphQLAPI\GraphQLAPI\ConditionalOnContext\Admin\SystemServices\TableActions\ModuleListTableAction;
use GraphQLAPI\GraphQLAPI\Constants\RequestParams;
use GraphQLAPI\GraphQLAPI\Facades\Registries\ModuleRegistryFacade;
use GraphQLAPI\GraphQLAPI\Facades\UserSettingsManagerFacade;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\PluginManagementFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\PluginConfiguration;
use GraphQLAPI\GraphQLAPI\Services\Helpers\MenuPageHelper;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\AboutMenuPage;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\ModulesMenuPage;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\SettingsMenuPage;
use GraphQLAPI\GraphQLAPI\PluginSkeleton\AbstractMainPlugin;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\Facades\Instances\SystemInstanceManagerFacade;
use PoP\Engine\AppLoader;

class Plugin extends AbstractMainPlugin
{
    /**
     * Plugin's namespace
     */
    public const NAMESPACE = __NAMESPACE__;

    /**
     * Show an admin notice with a link to the latest release notes
     */
    protected function pluginJustUpdated(string $storedVersion): void
    {
        parent::pluginJustUpdated($storedVersion);
        
        // Do not execute when doing Ajax, since we can't show the one-time
        // admin notice to the user then
        if (\wp_doing_ajax()) {
            return;
        }

        // Admin notice: Check if it is enabled
        $userSettingsManager = UserSettingsManagerFacade::getInstance();
        if (
            !$userSettingsManager->getSetting(
                PluginManagementFunctionalityModuleResolver::GENERAL,
                PluginManagementFunctionalityModuleResolver::OPTION_ADD_RELEASE_NOTES_ADMIN_NOTICE
            )
        ) {
            return;
        }
        // Show admin notice only when updating MAJOR or MINOR versions. No need for PATCH versions
        $currentMinorReleaseVersion = $this->getMinorReleaseVersion($this->pluginVersion);
        $previousMinorReleaseVersion = $this->getMinorReleaseVersion($storedVersion);
        if ($currentMinorReleaseVersion == $previousMinorReleaseVersion) {
            return;
        }
        // All checks passed, show the release notes
        $this->showReleaseNotesInAdminNotice();
    }

    /**
     * Add a notice with a link to the latest release note,
     * to open in a modal window
     */
    protected function showReleaseNotesInAdminNotice(): void
    {
        // Load the assets to open in a modal
        \add_action('admin_enqueue_scripts', function () {
            /**
             * Hack to open the modal thickbox iframe with the documentation
             */
            \wp_enqueue_style(
                'thickbox'
            );
            \wp_enqueue_script(
                'plugin-install'
            );
        });
        // Add the admin notice
        \add_action('admin_notices', function () {
            $instanceManager = InstanceManagerFacade::getInstance();
            /**
             * @var AboutMenuPage
             */
            $aboutMenuPage = $instanceManager->getInstance(AboutMenuPage::class);
            // Calculate the minor release version.
            // Eg: if current version is 0.6.3, minor version is 0.6
            $minorReleaseVersion = $this->getMinorReleaseVersion($this->pluginVersion);
            $releaseNotesURL = \admin_url(sprintf(
                'admin.php?page=%s&%s=%s&%s=%s&TB_iframe=true',
                $aboutMenuPage->getScreenID(),
                RequestParams::TAB,
                RequestParams::TAB_DOCS,
                RequestParams::DOC,
                sprintf(
                    'release-notes/%s',
                    $minorReleaseVersion
                )
            ));
            /**
             * @var SettingsMenuPage
             */
            $settingsMenuPage = $instanceManager->getInstance(SettingsMenuPage::class);
            $moduleRegistry = ModuleRegistryFacade::getInstance();
            $generalSettingsURL = \admin_url(sprintf(
                'admin.php?page=%s&tab=%s',
                $settingsMenuPage->getScreenID(),
                $moduleRegistry
                    ->getModuleResolver(PluginManagementFunctionalityModuleResolver::GENERAL)
                    ->getID(PluginManagementFunctionalityModuleResolver::GENERAL)
            ));
            _e(sprintf(
                '<div class="notice notice-success is-dismissible">' .
                '<p>%s</p>' .
                '</div>',
                sprintf(
                    __('Plugin <strong>GraphQL API for WordPress</strong> has been updated to version <code>%s</code>. <strong><a href="%s" class="%s">Check out what\'s new</a></strong> | <a href="%s">Disable this admin notice in the Settings</a>', 'graphql-api'),
                    $this->pluginVersion,
                    $releaseNotesURL,
                    'thickbox open-plugin-details-modal',
                    $generalSettingsURL
                )
            ));
        });
    }

    /**
     * Given a version in semver (MAJOR.MINOR.PATCH),
     * return the minor version (MAJOR.MINOR)
     */
    protected function getMinorReleaseVersion(string $version): string
    {
        $versionParts = explode('.', $version);
        return $versionParts[0] . '.' . $versionParts[1];
    }

    /**
     * Add Component classes to be initialized
     *
     * @return string[] List of `Component` class to initialize
     */
    public function getComponentClassesToInitialize(): array
    {
        return [
            Component::class,
        ];
    }

    /**
     * Add configuration for the Component classes
     *
     * @return array<string, mixed> [key]: Component class, [value]: Configuration
     */
    public function getComponentClassConfiguration(): array
    {
        return PluginConfiguration::getComponentClassConfiguration();
    }

    /**
     * Add schema Component classes to skip initializing
     *
     * @return string[] List of `Component` class which must not initialize their Schema services
     */
    public function getSchemaComponentClassesToSkip(): array
    {
        return PluginConfiguration::getSkippingSchemaComponentClasses();
    }

    /**
     * Boot the system
     */
    public function bootSystem(): void
    {
        // If the service container has an error, Symfony DI will throw an exception
        try {
            // Boot all PoP components, from this plugin and all extensions
            AppLoader::bootSystem(
                ...PluginConfiguration::getContainerCacheConfiguration()
            );

            /**
             * Watch out! If we are in the Modules page and enabling/disabling
             * a module, then already take that new state!
             *
             * This is because `maybeProcessAction`, which is where modules are
             * enabled/disabled, must be executed before PluginConfiguration::initialize(),
             * which is where the plugin reads if a module is enabled/disabled as to
             * set the environment constants.
             *
             * This is mandatory, because only when it is enabled, can a module
             * have its state persisted when calling `flush_rewrite`.
             *
             * For that, all the classes below have also been registered in system-services.yaml
             */
            if (\is_admin()) {
                // Obtain these services from the SystemContainer
                $systemInstanceManager = SystemInstanceManagerFacade::getInstance();
                /** @var MenuPageHelper */
                $menuPageHelper = $systemInstanceManager->getInstance(MenuPageHelper::class);
                /** @var ModulesMenuPage */
                $modulesMenuPage = $systemInstanceManager->getInstance(ModulesMenuPage::class);
                if (
                    (isset($_GET['page']) && $_GET['page'] == $modulesMenuPage->getScreenID())
                    && !$menuPageHelper->isDocumentationScreen()
                ) {
                    /** @var ModuleListTableAction */
                    $tableAction = $systemInstanceManager->getInstance(ModuleListTableAction::class);
                    $tableAction->maybeProcessAction();
                }
            }
        } catch (Exception $e) {
            $this->inititalizationException = $e;
        }
    }

    /**
     * Configure the plugin.
     * This defines hooks to set environment variables,
     * so must be executed before those hooks are triggered for first time
     * (in ComponentConfiguration classes)
     */
    protected function callPluginConfiguration(): void
    {
        PluginConfiguration::initialize();
    }

    /**
     * Boot the application
     */
    public function bootApplication(): void
    {
        // If the service container has an error, Symfony DI will throw an exception
        try {
            // Boot all PoP components, from this plugin and all extensions
            AppLoader::bootApplication(
                ...PluginConfiguration::getContainerCacheConfiguration()
            );
        } catch (Exception $e) {
            $this->inititalizationException = $e;
        }
    }
}
