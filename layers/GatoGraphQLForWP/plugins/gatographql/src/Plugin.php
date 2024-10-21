<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL;

use GatoGraphQL\GatoGraphQL\Assets\UseImageWidthsAssetsTrait;
use GatoGraphQL\GatoGraphQL\ConditionalOnContext\Admin\SystemServices\TableActions\ModuleListTableAction;
use GatoGraphQL\GatoGraphQL\Constants\RequestParams;
use GatoGraphQL\GatoGraphQL\Facades\Registries\ModuleRegistryFacade;
use GatoGraphQL\GatoGraphQL\Facades\Registries\SystemSettingsCategoryRegistryFacade;
use GatoGraphQL\GatoGraphQL\Facades\UserSettingsManagerFacade;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\PluginGeneralSettingsFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\PluginSkeleton\AbstractMainPlugin;
use GatoGraphQL\GatoGraphQL\Services\Helpers\MenuPageHelper;
use GatoGraphQL\GatoGraphQL\Services\MenuPages\AboutMenuPage;
use GatoGraphQL\GatoGraphQL\Services\MenuPages\ModulesMenuPage;
use GatoGraphQL\GatoGraphQL\Services\MenuPages\SettingsMenuPage;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\Root\Facades\Instances\SystemInstanceManagerFacade;
use PoP\Root\Module\ModuleInterface;

use function add_action;
use function is_admin;

class Plugin extends AbstractMainPlugin
{
    use UseImageWidthsAssetsTrait;

    /**
     * Plugin's namespace
     */
    public final const NAMESPACE = __NAMESPACE__;

    /**
     * When updating the plugin:
     *
     * - Add a banner asking users to rate the plugin
     * - Show an admin notice with a link to the latest release notes
     */
    public function pluginJustUpdated(string $newVersion, string $previousVersion): void
    {
        parent::pluginJustUpdated($newVersion, $previousVersion);

        // Do not execute when doing Ajax, since we can't show the one-time
        // admin notice to the user then
        if (\wp_doing_ajax()) {
            return;
        }

        // Admin notice: Check if it is enabled
        $userSettingsManager = UserSettingsManagerFacade::getInstance();
        if (
            !$userSettingsManager->getSetting(
                PluginGeneralSettingsFunctionalityModuleResolver::GENERAL,
                PluginGeneralSettingsFunctionalityModuleResolver::OPTION_ADD_RELEASE_NOTES_ADMIN_NOTICE
            )
        ) {
            return;
        }

        // Show admin notice only when updating MAJOR or MINOR versions. No need for PATCH versions
        $currentMinorReleaseVersion = $this->getMinorReleaseVersion($newVersion);
        $previousMinorReleaseVersion = $this->getMinorReleaseVersion($previousVersion);
        if ($currentMinorReleaseVersion === $previousMinorReleaseVersion) {
            return;
        }
        // All checks passed, show the release notes
        $this->showReleaseNotesInAdminNotice();

        if ($this->enableShowingRatePluginBannerInAdminNotice()) {
            $this->showRatePluginBannerInAdminNotice();
        }
    }

    protected function enableShowingRatePluginBannerInAdminNotice(): bool
    {
        return true;
    }

    /**
     * Add a banner asking users to rate the plugin
     */
    protected function showRatePluginBannerInAdminNotice(): void
    {
        // Add the admin notice
        add_action('admin_notices', function (): void {
            $adminNotice_safe = sprintf(
                '<div class="notice notice-info is-dismissible">' .
                    '<h3>%s</h3>' .
                    '<p>%s</p>' .
                    '<p>%s</p>' .
                '</div>',
                __('Please rate Gato GraphQL ❤️', 'gatographql'),
                __('We work really hard to deliver a plugin that converts the WordPress site into a full-fledged GraphQL server. It takes plenty of time and effort to develop, test and maintain the free Gato GraphQL plugin. Therefore if you like what you see and appreciate our work, we ask you nothing more than to please rate the plugin in the directory. Thanks in advance!', 'gatographql'),
                sprintf(
                    '<a class="rating-link" rel="noopener noreferrer" href="https://wordpress.org/plugins/gatographql/#reviews" target="_blank"><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span></a> <a class="button" rel="noopener noreferrer" href="https://wordpress.org/plugins/gatographql/#reviews" target="_blank">%s</a>',
                    \__('Rate Plugin', 'gatographql')
                ),
            );
            echo $adminNotice_safe;
        });
    }

    /**
     * Add a notice with a link to the latest release note,
     * to open in a modal window
     */
    protected function showReleaseNotesInAdminNotice(): void
    {
        // Load the assets to open in a modal
        add_action('admin_enqueue_scripts', function (): void {
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
        add_action('admin_notices', function (): void {
            $instanceManager = InstanceManagerFacade::getInstance();
            $settingsCategoryRegistry = SystemSettingsCategoryRegistryFacade::getInstance();
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
                    '../../release-notes/%s',
                    $minorReleaseVersion
                )
            ));
            /**
             * @var SettingsMenuPage
             */
            $settingsMenuPage = $instanceManager->getInstance(SettingsMenuPage::class);
            $moduleRegistry = ModuleRegistryFacade::getInstance();
            $generalSettingsModuleResolver = $moduleRegistry->getModuleResolver(PluginGeneralSettingsFunctionalityModuleResolver::GENERAL);
            $generalSettingsCategory = $generalSettingsModuleResolver->getSettingsCategory(PluginGeneralSettingsFunctionalityModuleResolver::GENERAL);
            $generalSettingsURL = \admin_url(sprintf(
                'admin.php?page=%s&%s=%s&%s=%s',
                $settingsMenuPage->getScreenID(),
                RequestParams::CATEGORY,
                $settingsCategoryRegistry->getSettingsCategoryResolver($generalSettingsCategory)->getID($generalSettingsCategory),
                RequestParams::MODULE,
                $generalSettingsModuleResolver->getID(PluginGeneralSettingsFunctionalityModuleResolver::GENERAL)
            ));
            $adminNotice_safe = sprintf(
                '<div class="notice notice-success is-dismissible">' .
                    '<p>%s</p>' .
                '</div>',
                sprintf(
                    __('Plugin <strong>Gato GraphQL</strong> has been updated to version <code>%s</code>. <strong><a href="%s" class="%s">Check out what\'s new</a></strong> | <a href="%s">Disable this admin notice in the Settings</a>', 'gatographql'),
                    $this->pluginVersion,
                    $releaseNotesURL,
                    'thickbox open-plugin-details-modal',
                    $generalSettingsURL
                )
            );
            echo $adminNotice_safe;
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
     * Add Module classes to be initialized
     *
     * @return array<class-string<ModuleInterface>> List of `Module` class to initialize
     */
    protected function getModuleClassesToInitialize(): array
    {
        return [
            Module::class,
        ];
    }

    /**
     * Boot the system
     */
    protected function doBootSystem(): void
    {
        /**
         * Watch out! If we are in the Modules page and enabling/disabling
         * a module, then already take that new state!
         *
         * This is because `maybeProcessAction`, which is where modules are
         * enabled/disabled, must be executed before PluginInitializationConfiguration->initialize(),
         * which is where the plugin reads if a module is enabled/disabled as to
         * set the environment constants.
         *
         * This is mandatory, because only when it is enabled, can a module
         * have its state persisted when calling `flush_rewrite`.
         *
         * For that, all the classes below have also been registered in system-services.yaml
         */
        if (is_admin()) {
            // Obtain these services from the SystemContainer
            $systemInstanceManager = SystemInstanceManagerFacade::getInstance();
            /** @var MenuPageHelper */
            $menuPageHelper = $systemInstanceManager->getInstance(MenuPageHelper::class);
            /** @var ModulesMenuPage */
            $modulesMenuPage = $systemInstanceManager->getInstance(ModulesMenuPage::class);
            if (
                $modulesMenuPage->isServiceEnabled() &&
                (App::query('page') === $modulesMenuPage->getScreenID()) &&
                !$menuPageHelper->isDocumentationScreen()
            ) {
                /** @var ModuleListTableAction */
                $tableAction = $systemInstanceManager->getInstance(ModuleListTableAction::class);
                $tableAction->maybeProcessAction();
            }
        }
    }

    /**
     * Dependencies on other plugins, to regenerate the schema
     * when these are activated/deactived
     *
     * @return string[]
     */
    public function getDependentOnPluginFiles(): array
    {
        return [
            'classic-editor/classic-editor.php',
        ];
    }

    protected function doBootApplication(): void
    {
        parent::doBootApplication();

        /**
         * Load the image width classes also within the Gutenberg editor,
         * to be used within the documentation modal windows.
         */
        add_action(
            'enqueue_block_editor_assets',
            $this->enqueueImageWidthsAssets(...)
        );
    }
}
