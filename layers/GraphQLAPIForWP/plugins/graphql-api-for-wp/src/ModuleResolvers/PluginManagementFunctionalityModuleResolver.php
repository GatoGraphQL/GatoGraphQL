<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\Constants\ResetSettingsOptions;
use GraphQLAPI\GraphQLAPI\ContentProcessors\MarkdownContentParserInterface;
use GraphQLAPI\GraphQLAPI\ModuleSettings\Properties;
use GraphQLAPI\GraphQLAPI\Plugin;
use GraphQLAPI\GraphQLAPI\Registries\SettingsCategoryRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\SettingsMenuPage;
use GraphQLAPI\GraphQLAPI\SettingsCategoryResolvers\SettingsCategoryResolver;
use GraphQLAPI\GraphQLAPI\StaticHelpers\BehaviorHelpers;

use function get_submit_button;

class PluginManagementFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver
{
    use ModuleResolverTrait;
    use PluginManagementFunctionalityModuleResolverTrait;

    public final const RESET_SETTINGS = Plugin::NAMESPACE . '\plugin-management';

    /**
     * Setting options
     */
    public final const OPTION_USE_SAFE_OR_UNSAFE_DEFAULT_BEHAVIOR = 'use-safe-or-unsafe-default-behavior';

    private ?MarkdownContentParserInterface $markdownContentParser = null;
    private ?SettingsCategoryRegistryInterface $settingsCategoryRegistry = null;
    private ?PluginManagementFunctionalityModuleResolver $pluginManagementFunctionalityModuleResolver = null;

    final public function setMarkdownContentParser(MarkdownContentParserInterface $markdownContentParser): void
    {
        $this->markdownContentParser = $markdownContentParser;
    }
    final protected function getMarkdownContentParser(): MarkdownContentParserInterface
    {
        /** @var MarkdownContentParserInterface */
        return $this->markdownContentParser ??= $this->instanceManager->getInstance(MarkdownContentParserInterface::class);
    }
    final public function setSettingsCategoryRegistry(SettingsCategoryRegistryInterface $settingsCategoryRegistry): void
    {
        $this->settingsCategoryRegistry = $settingsCategoryRegistry;
    }
    final protected function getSettingsCategoryRegistry(): SettingsCategoryRegistryInterface
    {
        /** @var SettingsCategoryRegistryInterface */
        return $this->settingsCategoryRegistry ??= $this->instanceManager->getInstance(SettingsCategoryRegistryInterface::class);
    }
    final public function setPluginManagementFunctionalityModuleResolver(PluginManagementFunctionalityModuleResolver $pluginManagementFunctionalityModuleResolver): void
    {
        $this->pluginManagementFunctionalityModuleResolver = $pluginManagementFunctionalityModuleResolver;
    }
    final protected function getPluginManagementFunctionalityModuleResolver(): PluginManagementFunctionalityModuleResolver
    {
        /** @var PluginManagementFunctionalityModuleResolver */
        return $this->pluginManagementFunctionalityModuleResolver ??= $this->instanceManager->getInstance(PluginManagementFunctionalityModuleResolver::class);
    }

    /**
     * @return string[]
     */
    public function getModulesToResolve(): array
    {
        return [
            self::RESET_SETTINGS,
        ];
    }

    public function isPredefinedEnabledOrDisabled(string $module): ?bool
    {
        return match ($module) {
            self::RESET_SETTINGS => true,
            default => parent::isPredefinedEnabledOrDisabled($module),
        };
    }

    public function isHidden(string $module): bool
    {
        return match ($module) {
            self::RESET_SETTINGS => true,
            default => parent::isHidden($module),
        };
    }

    public function getName(string $module): string
    {
        return match ($module) {
            self::RESET_SETTINGS => \__('Reset Settings', 'graphql-api'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        return match ($module) {
            self::RESET_SETTINGS => \__('Restore the GraphQL API Settings to default values', 'graphql-api'),
            default => parent::getDescription($module),
        };
    }

    /**
     * Default value for an option set by the module
     */
    public function getSettingsDefaultValue(string $module, string $option): mixed
    {
        $useUnsafeDefaults = BehaviorHelpers::areUnsafeDefaultsEnabled();
        $defaultValues = [
            self::RESET_SETTINGS => [
                self::OPTION_USE_SAFE_OR_UNSAFE_DEFAULT_BEHAVIOR => $useUnsafeDefaults ? ResetSettingsOptions::UNSAFE : ResetSettingsOptions::SAFE,
            ],
        ];
        return $defaultValues[$module][$option] ?? null;
    }

    /**
     * Array with the inputs to show as settings for the module
     *
     * @return array<array<string,mixed>> List of settings for the module, each entry is an array with property => value
     */
    public function getSettings(string $module): array
    {
        $moduleSettings = parent::getSettings($module);
        if ($module === self::RESET_SETTINGS) {
            $resetSettingsButtonsHTML = sprintf(
                <<<HTML
                    <a href="#" class="button secondary graphql-api-show-settings-items">
                        %1\$s
                    </a>
                HTML,
                \__('Show options to reset the Settings', 'graphql-api')
            );
            $moduleSettings[] = [
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    'reset-settings-button'
                ),
                Properties::TITLE => \__('Reset the GraphQL API Settings?', 'graphql-api'),
                Properties::DESCRIPTION => sprintf(
                    '<p>%s</p><p>%s</p>',
                    \__('Restore the GraphQL API Settings to default values.', 'graphql-api'),
                    $resetSettingsButtonsHTML
                ),
                Properties::TYPE => Properties::TYPE_NULL,
            ];

            $moduleSettings[] = [
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    'safe-or-unsafe-behavior-description'
                ),
                Properties::DESCRIPTION => sprintf(
                    '<p>%s</p><br/><p>%s</p><p>%s</p><ul><li>%s</li></ul><br/><p>%s</p><p>%s</p><ul><li>%s</li></ul>',
                    \__('When the settings are reset, the default values can follow a "safe" or "unsafe" behavior:', 'graphql-api'),
                    \__('<strong><u>Safe default settings</u></strong>', 'graphql-api'),
                    \__('Recommended when the site openly exposes APIs (eg: for any visitor on the Internet, or for clients, or when feeding data to a downstream server an a non-private network), as to make the site secure:', 'graphql-api'),
                    implode(
                        '</li><li>',
                        [
                            \__('The single endpoint is disabled', 'graphql-api'),
                            \__('The “sensitive” data elements (eg: input field <code>status</code> to query posts with status <code>"draft"</code>) are not added to the schema', 'graphql-api'),
                            \__('Only a few of settings options and meta keys (for posts, users, etc) can be queried', 'graphql-api'),
                            \__('The number of entities (for posts, users, etc) that can be queried at once is limited', 'graphql-api'),
                        ]
                    ),
                    \__('<strong><u>Unsafe default settings</u></strong>', 'graphql-api'),
                    \__('Recommended when the WordPress site is not publicly exposed, i.e. when only available on a private or internal network (as when building static sites):', 'graphql-api'),
                    implode(
                        '</li><li>',
                        [
                            \__('The single endpoint is enabled', 'graphql-api'),
                            \__('The “sensitive” data elements are exposed in the schema', 'graphql-api'),
                            \__('All settings options and meta keys can be queried', 'graphql-api'),
                            \__('The number of entities that can be queried at once is unlimited', 'graphql-api'),
                        ]
                    ),
                ),
                Properties::TYPE => Properties::TYPE_NULL,
                Properties::CSS_STYLE => 'display: none;',
            ];

            $option = self::OPTION_USE_SAFE_OR_UNSAFE_DEFAULT_BEHAVIOR;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::DESCRIPTION => sprintf(
                    '<p>%s</p>',
                    \__('Choose to use either the "safe" or "unsafe" default settings.', 'graphql-api'),
                ),
                Properties::TYPE => Properties::TYPE_STRING,
                Properties::POSSIBLE_VALUES => [
                    ResetSettingsOptions::SAFE => \__('Use "safe" default behavior for the Settings', 'graphql-api'),
                    ResetSettingsOptions::UNSAFE => \__('Use "unsafe" default behavior for the Settings', 'graphql-api'),
                ],
                Properties::CSS_STYLE => 'display: none;',
            ];
            /**
             * Have the reset button name be sent as part of the form
             */
            $resetButtonName = sprintf(
                '%s[%s]',
                $this->getSettingsCategoryRegistry()->getSettingsCategoryResolver(SettingsCategoryResolver::PLUGIN_MANAGEMENT)->getOptionsFormName(SettingsCategoryResolver::PLUGIN_MANAGEMENT),
                SettingsMenuPage::RESET_SETTINGS_BUTTON_ID
            );
            /**
             * Use `function_exists` because, when pressing on
             * the button it will call options.php,
             * and the function will not have been loaded yet!
             */
            $confirmResetSettingsButtonsHTML = '';
            if (function_exists('get_submit_button')) {
                $confirmResetSettingsButtonsHTML = get_submit_button(
                    \__('Confirm: Reset Settings', 'graphql-api'),
                    'primary',
                    $resetButtonName,
                    false
                );
            }
            $moduleSettings[] = [
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    'confirm-reset-settings-button'
                ),
                Properties::DESCRIPTION => $confirmResetSettingsButtonsHTML,
                Properties::TYPE => Properties::TYPE_NULL,
                Properties::CSS_STYLE => 'display: none;',
            ];
        }
        return $moduleSettings;
    }
}
