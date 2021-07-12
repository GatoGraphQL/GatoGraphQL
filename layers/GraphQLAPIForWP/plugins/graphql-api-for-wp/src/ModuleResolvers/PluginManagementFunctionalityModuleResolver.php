<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\ModuleResolverTrait;
use GraphQLAPI\GraphQLAPI\ModuleSettings\Properties;
use GraphQLAPI\GraphQLAPI\Plugin;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Registries\UserAuthorizationSchemeRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\ModuleTypeResolvers\ModuleTypeResolver;
use PoP\Translation\TranslationAPIInterface;

class PluginManagementFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver
{
    use ModuleResolverTrait;

    public const GENERAL = Plugin::NAMESPACE . '\general';

    /**
     * Setting options
     */
    public const OPTION_ADD_RELEASE_NOTES_ADMIN_NOTICE = 'add-release-notes-admin-notice';
    public const OPTION_PRINT_SETTINGS_WITH_TABS = 'print-settings-with-tabs';

    public function __construct(
        ModuleRegistryInterface $moduleRegistry,
        TranslationAPIInterface $translationAPI,
        protected UserAuthorizationSchemeRegistryInterface $userAuthorizationSchemeRegistry,
    ) {
        parent::__construct(
            $moduleRegistry,
            $translationAPI,
        );
    }

    /**
     * @return string[]
     */
    public function getModulesToResolve(): array
    {
        return [
            self::GENERAL,
        ];
    }

    /**
     * The priority to display the modules from this resolver in the Modules page.
     * The higher the number, the earlier it shows
     */
    public function getPriority(): int
    {
        return 200;
    }

    /**
     * Enable to customize a specific UI for the module
     */
    public function getModuleType(string $module): string
    {
        return ModuleTypeResolver::PLUGIN_MANAGEMENT;
    }

    public function canBeDisabled(string $module): bool
    {
        switch ($module) {
            case self::GENERAL:
                return false;
        }
        return parent::canBeDisabled($module);
    }

    public function isHidden(string $module): bool
    {
        switch ($module) {
            case self::GENERAL:
                return true;
        }
        return parent::isHidden($module);
    }

    public function getName(string $module): string
    {
        $names = [
            self::GENERAL => \__('General', 'graphql-api'),
        ];
        return $names[$module] ?? $module;
    }

    public function getDescription(string $module): string
    {
        switch ($module) {
            case self::GENERAL:
                return \__('General options for the plugin', 'graphql-api');
        }
        return parent::getDescription($module);
    }

    /**
     * Default value for an option set by the module
     */
    public function getSettingsDefaultValue(string $module, string $option): mixed
    {
        $defaultUserAuthorizationScheme = $this->userAuthorizationSchemeRegistry->getDefaultUserAuthorizationScheme();
        $defaultValues = [
            self::GENERAL => [
                self::OPTION_ADD_RELEASE_NOTES_ADMIN_NOTICE => true,
                self::OPTION_PRINT_SETTINGS_WITH_TABS => true,
            ],
        ];
        return $defaultValues[$module][$option] ?? null;
    }

    /**
     * Array with the inputs to show as settings for the module
     *
     * @return array<array> List of settings for the module, each entry is an array with property => value
     */
    public function getSettings(string $module): array
    {
        $moduleSettings = parent::getSettings($module);
        if ($module == self::GENERAL) {
            $option = self::OPTION_ADD_RELEASE_NOTES_ADMIN_NOTICE;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Display admin notice with release notes?', 'graphql-api'),
                Properties::DESCRIPTION => \__('Immediately after upgrading the plugin, show an admin notice with a link to the latest release notes?', 'graphql-api'),
                Properties::TYPE => Properties::TYPE_BOOL,
            ];

            $option = self::OPTION_PRINT_SETTINGS_WITH_TABS;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Organize these settings under tabs?', 'graphql-api'),
                Properties::DESCRIPTION => \__('Have all options in this Settings page be organized under tabs, one tab per module.<br/>After ticking the checkbox, must click on "Save Changes" to be applied.', 'graphql-api'),
                Properties::TYPE => Properties::TYPE_BOOL,
            ];
        }
        return $moduleSettings;
    }
}
