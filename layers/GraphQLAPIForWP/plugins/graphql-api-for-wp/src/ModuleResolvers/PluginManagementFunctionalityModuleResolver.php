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

    public final const RESET_SETTINGS = Plugin::NAMESPACE . '\reset-settings';

    /**
     * Setting options
     */
    public final const OPTION_USE_RESTRICTIVE_OR_NOT_DEFAULT_BEHAVIOR = 'use-restrictive-or-not-default-behavior';

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
        $useRestrictiveDefaults = BehaviorHelpers::areRestrictiveDefaultsEnabled();
        $defaultValues = [
            self::RESET_SETTINGS => [
                self::OPTION_USE_RESTRICTIVE_OR_NOT_DEFAULT_BEHAVIOR => $useRestrictiveDefaults ? ResetSettingsOptions::RESTRICTIVE : ResetSettingsOptions::NON_RESTRICTIVE,
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
            $resetSettingsCategories = [
                'schema' => SettingsCategoryResolver::SCHEMA_CONFIGURATION,
                'endpoint' => SettingsCategoryResolver::ENDPOINT_CONFIGURATION,
                'plugin' => SettingsCategoryResolver::PLUGIN_CONFIGURATION,
                'license-keys' => SettingsCategoryResolver::PLUGIN_CONFIGURATION,
            ];
            $settingsCategoryRegistry = $this->getSettingsCategoryRegistry();
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
                    sprintf(
                        \__('Restore all settings under tabs "%s", "%s" and "%s" to their default values.', 'graphql-api'),
                        $settingsCategoryRegistry->getSettingsCategoryResolver(SettingsCategoryResolver::SCHEMA_CONFIGURATION)->getName(SettingsCategoryResolver::SCHEMA_CONFIGURATION),
                        $settingsCategoryRegistry->getSettingsCategoryResolver(SettingsCategoryResolver::ENDPOINT_CONFIGURATION)->getName(SettingsCategoryResolver::ENDPOINT_CONFIGURATION),
                        $settingsCategoryRegistry->getSettingsCategoryResolver(SettingsCategoryResolver::PLUGIN_CONFIGURATION)->getName(SettingsCategoryResolver::PLUGIN_CONFIGURATION),
                    ),
                    $resetSettingsButtonsHTML
                ),
                Properties::TYPE => Properties::TYPE_NULL,
            ];

            $moduleSettings[] = [
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    'restrictive-or-not-behavior-description'
                ),
                Properties::DESCRIPTION => sprintf(
                    '<p>%s</p><br/><table class="wp-list-table widefat striped"><thead><tr><th>%s</th><th>%s</th><th>%s</th></tr></thead><tbody><tr>%s</tr></tbody></table>',
                    \__('When the settings are reset, the default values can follow a restrictive or non-restrictive behavior:', 'graphql-api'),
                    \__('Feature', 'graphql-api'),
                    \__('Non-restrictive behavior', 'graphql-api'),
                    \__('Restrictive behavior', 'graphql-api'),
                    implode(
                        '</tr><tr>',
                        [
                            '<td>' . implode(
                                '</td><td>',
                                [
                                    \__('Single endpoint', 'graphql-api'),
                                    \__('Enabled', 'graphql-api'),
                                    \__('Disabled', 'graphql-api'),
                                ]
                            ) . '</td>',
                            '<td>' . implode(
                                '</td><td>',
                                [
                                    \__('“Sensitive” data fields', 'graphql-api'),
                                    \__('Added to the schema', 'graphql-api'),
                                    \__('Not added to the schema', 'graphql-api'),
                                ]
                            ) . '</td>',
                            '<td>' . implode(
                                '</td><td>',
                                [
                                    \__('Settings from <code>wp_options</code>', 'graphql-api'),
                                    \__('All options are queryable', 'graphql-api'),
                                    \__('Only a few predefined options are queryable', 'graphql-api'),
                                ]
                            ) . '</td>',
                            '<td>' . implode(
                                '</td><td>',
                                [
                                    \__('Meta (posts, users, comments, taxonomies)', 'graphql-api'),
                                    \__('All keys are queryable', 'graphql-api'),
                                    \__('No keys are queryable', 'graphql-api'),
                                ]
                            ) . '</td>',
                            '<td>' . implode(
                                '</td><td>',
                                [
                                    \__('Max limit to query entities (posts, users, etc)', 'graphql-api'),
                                    \__('Unlimited', 'graphql-api'),
                                    \__('Limited', 'graphql-api'),
                                ]
                            ) . '</td>',
                            '<td>' . implode(
                                '</td><td>',
                                [
                                    \__('Environment Fields', 'graphql-api'),
                                    \__('All environment variables and PHP constants are queryable', 'graphql-api'),
                                    \__('No environment variables or PHP constants are queryable', 'graphql-api'),
                                ]
                            ) . '</td>',
                            '<td>' . implode(
                                '</td><td>',
                                [
                                    \__('Send HTTP Request Fields', 'graphql-api'),
                                    \__('All URLs can be requested', 'graphql-api'),
                                    \__('No URL can be requested', 'graphql-api'),
                                ]
                            ) . '</td>',
                        ]
                    ),
                ),
                Properties::TYPE => Properties::TYPE_NULL,
                Properties::CSS_STYLE => 'display: none;',
            ];

            $option = self::OPTION_USE_RESTRICTIVE_OR_NOT_DEFAULT_BEHAVIOR;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::DESCRIPTION => sprintf(
                    '<p>%s</p>',
                    \__('Choose if to use restrictive or non-restrictive default settings.', 'graphql-api'),
                ),
                Properties::TYPE => Properties::TYPE_STRING,
                Properties::POSSIBLE_VALUES => [
                    ResetSettingsOptions::RESTRICTIVE => \__('Use the restrictive default behavior for the Settings', 'graphql-api'),
                    ResetSettingsOptions::NON_RESTRICTIVE => \__('Use the non-restrictive default behavior for the Settings', 'graphql-api'),
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
