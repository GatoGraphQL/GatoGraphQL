<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\Constants\ResetSettingsOptions;
use GatoGraphQL\GatoGraphQL\ContentProcessors\MarkdownContentParserInterface;
use GatoGraphQL\GatoGraphQL\Facades\Registries\CustomPostTypeRegistryFacade;
use GatoGraphQL\GatoGraphQL\ModuleSettings\Properties;
use GatoGraphQL\GatoGraphQL\Plugin;
use GatoGraphQL\GatoGraphQL\PluginApp;
use GatoGraphQL\GatoGraphQL\Registries\SettingsCategoryRegistryInterface;
use GatoGraphQL\GatoGraphQL\Services\MenuPages\SettingsMenuPage;
use GatoGraphQL\GatoGraphQL\SettingsCategoryResolvers\SettingsCategoryResolver;
use GatoGraphQL\GatoGraphQL\StaticHelpers\BehaviorHelpers;

use function esc_html;
use function get_post_type_object;
use function get_submit_button;

class PluginManagementFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver
{
    use ModuleResolverTrait;
    use PluginManagementFunctionalityModuleResolverTrait;

    public final const ACTIVATE_EXTENSIONS = Plugin::NAMESPACE . '\activate-extensions';
    public final const RESET_SETTINGS = Plugin::NAMESPACE . '\reset-settings';
    public final const UNINSTALL = Plugin::NAMESPACE . '\uninstall';

    /**
     * Setting options
     */
    public final const OPTION_COMMERCIAL_EXTENSION_LICENSE_KEYS = 'commercial-extension-license-keys';
    public final const OPTION_USE_RESTRICTIVE_OR_NOT_DEFAULT_BEHAVIOR = 'use-restrictive-or-not-default-behavior';
    public final const OPTION_DELETE_DATA_ON_UNINSTALL = 'delete-data-on-uninstall';
    public final const OPTION_DELETE_CONTENT_ON_UNINSTALL = 'delete-content-on-uninstall';

    private ?MarkdownContentParserInterface $markdownContentParser = null;
    private ?SettingsCategoryRegistryInterface $settingsCategoryRegistry = null;
    private ?PluginManagementFunctionalityModuleResolver $pluginManagementFunctionalityModuleResolver = null;

    final protected function getMarkdownContentParser(): MarkdownContentParserInterface
    {
        if ($this->markdownContentParser === null) {
            /** @var MarkdownContentParserInterface */
            $markdownContentParser = $this->instanceManager->getInstance(MarkdownContentParserInterface::class);
            $this->markdownContentParser = $markdownContentParser;
        }
        return $this->markdownContentParser;
    }
    final protected function getSettingsCategoryRegistry(): SettingsCategoryRegistryInterface
    {
        if ($this->settingsCategoryRegistry === null) {
            /** @var SettingsCategoryRegistryInterface */
            $settingsCategoryRegistry = $this->instanceManager->getInstance(SettingsCategoryRegistryInterface::class);
            $this->settingsCategoryRegistry = $settingsCategoryRegistry;
        }
        return $this->settingsCategoryRegistry;
    }
    final protected function getPluginManagementFunctionalityModuleResolver(): PluginManagementFunctionalityModuleResolver
    {
        if ($this->pluginManagementFunctionalityModuleResolver === null) {
            /** @var PluginManagementFunctionalityModuleResolver */
            $pluginManagementFunctionalityModuleResolver = $this->instanceManager->getInstance(PluginManagementFunctionalityModuleResolver::class);
            $this->pluginManagementFunctionalityModuleResolver = $pluginManagementFunctionalityModuleResolver;
        }
        return $this->pluginManagementFunctionalityModuleResolver;
    }

    /**
     * @return string[]
     */
    public function getModulesToResolve(): array
    {
        return [
            self::ACTIVATE_EXTENSIONS,
            self::RESET_SETTINGS,
            self::UNINSTALL,
        ];
    }

    public function isPredefinedEnabledOrDisabled(string $module): ?bool
    {
        return match ($module) {
            self::ACTIVATE_EXTENSIONS,
            self::RESET_SETTINGS,
            self::UNINSTALL
                => true,
            default
                => parent::isPredefinedEnabledOrDisabled($module),
        };
    }

    public function isHidden(string $module): bool
    {
        return match ($module) {
            self::ACTIVATE_EXTENSIONS,
            self::RESET_SETTINGS,
            self::UNINSTALL
                => true,
            default
                => parent::isHidden($module),
        };
    }

    public function getName(string $module): string
    {
        return match ($module) {
            self::ACTIVATE_EXTENSIONS => \__('Activate Plugins and Extensions', 'gatographql'),
            self::RESET_SETTINGS => \__('Reset Settings', 'gatographql'),
            self::UNINSTALL => \__('Uninstall', 'gatographql'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        return match ($module) {
            self::ACTIVATE_EXTENSIONS => sprintf(
                \__('Activate Plugins and Extensions from the %s', 'gatographql'),
                $this->getGatoGraphQLShopName()
            ),
            self::RESET_SETTINGS => \__('Restore the Gato GraphQL Settings to default values', 'gatographql'),
            self::UNINSTALL => \__('Choose what happens to the data stored by the plugin when the plugin is deleted', 'gatographql'),
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
            self::ACTIVATE_EXTENSIONS => [
                self::OPTION_COMMERCIAL_EXTENSION_LICENSE_KEYS => [],
            ],
            self::RESET_SETTINGS => [
                self::OPTION_USE_RESTRICTIVE_OR_NOT_DEFAULT_BEHAVIOR => $useRestrictiveDefaults ? ResetSettingsOptions::RESTRICTIVE : ResetSettingsOptions::NON_RESTRICTIVE,
            ],
            self::UNINSTALL => [
                self::OPTION_DELETE_DATA_ON_UNINSTALL => false,
                self::OPTION_DELETE_CONTENT_ON_UNINSTALL => false,
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
        if ($module === self::ACTIVATE_EXTENSIONS) {
            $showNoCommercialExtensionsInstalledMessage = false;
            $extensionManager = PluginApp::getExtensionManager();
            $commercialExtensionSlugProductNames = $extensionManager->getCommercialExtensionSlugProductNames();
            $activateExtensionLicensesTitle = $this->getActivateExtensionLicensesTitle();
            if ($commercialExtensionSlugProductNames !== []) {
                $ulPlaceholder = '<ul><li>%s</li></ul>';
                $handlingLicenseMessageItems = [
                    \__('Adding a license key will <strong>activate the plugin</strong>', 'gatographql'),
                    \__('Removing an existing license key will <strong>deactivate the plugin</strong>', 'gatographql'),
                    \__('Updating a license key will first <strong>deactivate the plugin</strong> (using the previous license key) and then <strong>activate the plugin</strong> again (using the new license key)', 'gatographql'),
                    \__('Not updating a license key will <strong>validate the status of the plugin</strong>', 'gatographql'),
                ];
                $option = self::OPTION_COMMERCIAL_EXTENSION_LICENSE_KEYS;
                $moduleSettings[] = [
                    Properties::INPUT => $option,
                    Properties::NAME => $this->getSettingOptionName(
                        $module,
                        $option
                    ),
                    Properties::TITLE => $activateExtensionLicensesTitle,
                    Properties::DESCRIPTION => sprintf(
                        '%s<br/><br/>%s',
                        sprintf(
                            \__('Enter the license keys from the %s, and click on <strong>Activate licenses (or Deactivate/Validate)</strong>:', 'gatographql'),
                            $this->getGatoGraphQLShopName()
                        ),
                        $this->getCollapsible(
                            sprintf(
                                '%s%s',
                                \__('When clicking on <strong>Activate licenses (or Deactivate/Validate)</strong>, one of the following actions will take place:', 'gatographql'),
                                sprintf(
                                    $ulPlaceholder,
                                    implode(
                                        '</li><li>',
                                        $handlingLicenseMessageItems
                                    )
                                ),
                            ),
                            \__('(Show details: When are licenses activated, deactivated or validated?)', 'gatographql')
                        ),
                    ),
                    Properties::TYPE => Properties::TYPE_PROPERTY_ARRAY,
                    Properties::KEY_LABELS => $commercialExtensionSlugProductNames,
                    Properties::SUBTYPE => Properties::TYPE_PASSWORD,
                ];
                /**
                 * Have the activate button name be sent as part of the form
                 */
                $activateButtonName = sprintf(
                    '%s[%s]',
                    $this->getSettingsCategoryRegistry()->getSettingsCategoryResolver(SettingsCategoryResolver::PLUGIN_MANAGEMENT)->getOptionsFormName(SettingsCategoryResolver::PLUGIN_MANAGEMENT),
                    SettingsMenuPage::ACTIVATE_EXTENSIONS_BUTTON_ID
                );
                /**
                 * Use `function_exists` because, when pressing on
                 * the button it will call options.php,
                 * and the function will not have been loaded yet!
                 */
                $activateExtensionsButtonsHTML = '';
                if (function_exists('get_submit_button')) {
                    $activateExtensionsButtonsHTML = get_submit_button(
                        \__('Activate licenses (or Deactivate/Validate)', 'gatographql'),
                        'primary',
                        $activateButtonName,
                        false
                    );
                }
                $moduleSettings[] = [
                    Properties::NAME => $this->getSettingOptionName(
                        $module,
                        'activate-extensions-button'
                    ),
                    Properties::DESCRIPTION => $activateExtensionsButtonsHTML,
                    Properties::TYPE => Properties::TYPE_NULL,
                ];
            } elseif ($showNoCommercialExtensionsInstalledMessage) { // @phpstan-ignore-line
                $moduleSettings[] = [
                    Properties::NAME => $this->getSettingOptionName(
                        $module,
                        'activate-extensions'
                    ),
                    Properties::TITLE => $activateExtensionLicensesTitle,
                    Properties::DESCRIPTION => sprintf(
                        \__('<em>There are no Bundles or Extensions from the %s installed</em>', 'gatographql'),
                        $this->getGatoGraphQLShopName()
                    ),
                    Properties::TYPE => Properties::TYPE_NULL,
                ];
            }
        } elseif ($module === self::RESET_SETTINGS) {
            $settingsCategoryRegistry = $this->getSettingsCategoryRegistry();
            $resetSettingsButtonsHTML = sprintf(
                '
                    <a href="#" class="button secondary gatographql-show-settings-items">
                        %1$s
                    </a>
                ',
                \__('Show options to reset the Settings', 'gatographql')
            );
            $moduleSettings[] = [
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    'reset-settings-button'
                ),
                Properties::TITLE => \__('Reset the Gato GraphQL Settings?', 'gatographql'),
                Properties::DESCRIPTION => sprintf(
                    '<p>%s</p><p>%s</p>',
                    sprintf(
                        \__('Restore all settings (under tabs <code>%s</code>, <code>%s</code>, <code>%s</code>, <code>%s</code> and <code>%s</code>) to their default values.', 'gatographql'),
                        $settingsCategoryRegistry->getSettingsCategoryResolver(SettingsCategoryResolver::ENDPOINT_CONFIGURATION)->getName(SettingsCategoryResolver::ENDPOINT_CONFIGURATION),
                        $settingsCategoryRegistry->getSettingsCategoryResolver(SettingsCategoryResolver::SCHEMA_CONFIGURATION)->getName(SettingsCategoryResolver::SCHEMA_CONFIGURATION),
                        $settingsCategoryRegistry->getSettingsCategoryResolver(SettingsCategoryResolver::SCHEMA_TYPE_CONFIGURATION)->getName(SettingsCategoryResolver::SCHEMA_TYPE_CONFIGURATION),
                        $settingsCategoryRegistry->getSettingsCategoryResolver(SettingsCategoryResolver::SERVER_CONFIGURATION)->getName(SettingsCategoryResolver::SERVER_CONFIGURATION),
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
                    \__('When the settings are reset, the default values can follow a restrictive or non-restrictive behavior:', 'gatographql'),
                    \__('Feature', 'gatographql'),
                    \__('Non-restrictive behavior', 'gatographql'),
                    \__('Restrictive behavior', 'gatographql'),
                    implode(
                        '</tr><tr>',
                        [
                            // '<td>' . implode(
                            //     '</td><td>',
                            //     [
                            //         \__('Single endpoint', 'gatographql'),
                            //         \__('Enabled', 'gatographql'),
                            //         \__('Disabled', 'gatographql'),
                            //     ]
                            // ) . '</td>',
                            '<td>' . implode(
                                '</td><td>',
                                [
                                    \__('“Sensitive” data fields', 'gatographql'),
                                    \__('Added to the schema', 'gatographql'),
                                    \__('Not added to the schema', 'gatographql'),
                                ]
                            ) . '</td>',
                            '<td>' . implode(
                                '</td><td>',
                                [
                                    \__('Settings from <code>wp_options</code>', 'gatographql'),
                                    \__('No options are queryable by non-administrators', 'gatographql'),
                                    \__('Only a few predefined options are queryable by non-administrators', 'gatographql'),
                                ]
                            ) . '</td>',
                            '<td>' . implode(
                                '</td><td>',
                                [
                                    \__('Meta (posts, users, comments, taxonomies)', 'gatographql'),
                                    \__('All keys are queryable', 'gatographql'),
                                    \__('No keys are queryable', 'gatographql'),
                                ]
                            ) . '</td>',
                            '<td>' . implode(
                                '</td><td>',
                                [
                                    \__('Max limit to query entities (posts, users, etc)', 'gatographql'),
                                    \__('Unlimited', 'gatographql'),
                                    \__('Limited', 'gatographql'),
                                ]
                            ) . '</td>',
                            '<td>' . implode(
                                '</td><td>',
                                [
                                    \__('Environment variables (extension)', 'gatographql'),
                                    \__('All environment variables and PHP constants are queryable', 'gatographql'),
                                    \__('No environment variables or PHP constants are queryable', 'gatographql'),
                                ]
                            ) . '</td>',
                            '<td>' . implode(
                                '</td><td>',
                                [
                                    \__('HTTP Client requests (extension)', 'gatographql'),
                                    \__('All URLs can be requested', 'gatographql'),
                                    \__('No URL can be requested', 'gatographql'),
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
                    \__('Choose if to use restrictive or non-restrictive default settings.', 'gatographql'),
                ),
                Properties::TYPE => Properties::TYPE_STRING,
                Properties::POSSIBLE_VALUES => [
                    ResetSettingsOptions::RESTRICTIVE => \__('Use the restrictive default behavior for the Settings', 'gatographql'),
                    ResetSettingsOptions::NON_RESTRICTIVE => \__('Use the non-restrictive default behavior for the Settings', 'gatographql'),
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
                    \__('Confirm: Reset Settings', 'gatographql'),
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
        } elseif ($module === self::UNINSTALL) {
            $option = self::OPTION_DELETE_DATA_ON_UNINSTALL;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Delete all plugin data when deleting the plugin?', 'gatographql'),
                Properties::DESCRIPTION => \__('Remove everything the plugin has stored on this site: its settings, the license records of its extensions, the metadata it added to your content and users, its database tables, and its cache and log files. Removing a license record does not release the license on the marketplace: to use it on another site, first clear its key under Activate Plugins and Extensions and save.', 'gatographql'),
                Properties::TYPE => Properties::TYPE_BOOL,
            ];

            $option = self::OPTION_DELETE_CONTENT_ON_UNINSTALL;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Also delete the entries created with the plugin?', 'gatographql'),
                Properties::DESCRIPTION => $this->getDeleteContentOnUninstallDescription(),
                Properties::TYPE => Properties::TYPE_BOOL,
            ];

            /**
             * Have the button name be sent as part of the form, as this
             * Settings category has no submit button of its own
             * {@see SettingsCategoryResolver::addOptionsFormSubmitButton()}.
             */
            $saveUninstallSettingsButtonName = sprintf(
                '%s[%s]',
                $this->getSettingsCategoryRegistry()->getSettingsCategoryResolver(SettingsCategoryResolver::PLUGIN_MANAGEMENT)->getOptionsFormName(SettingsCategoryResolver::PLUGIN_MANAGEMENT),
                SettingsMenuPage::SAVE_UNINSTALL_SETTINGS_BUTTON_ID
            );
            /**
             * Use `function_exists` because, when pressing on
             * the button it will call options.php,
             * and the function will not have been loaded yet!
             */
            $saveUninstallSettingsButtonHTML = '';
            if (function_exists('get_submit_button')) {
                $saveUninstallSettingsButtonHTML = get_submit_button(
                    \__('Save Uninstall Settings', 'gatographql'),
                    'primary',
                    $saveUninstallSettingsButtonName,
                    false
                );
            }
            $moduleSettings[] = [
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    'save-uninstall-settings-button'
                ),
                Properties::DESCRIPTION => $saveUninstallSettingsButtonHTML,
                Properties::TYPE => Properties::TYPE_NULL,
            ];
        }
        return $moduleSettings;
    }

    /**
     * Name the entries the user would actually lose, by asking WordPress for
     * the labels of the custom post types this plugin registered, rather than
     * naming any of them here: which entries exist depends on which plugin
     * this is, and on which of its extensions are active.
     */
    protected function getDeleteContentOnUninstallDescription(): string
    {
        $customPostTypeRegistry = CustomPostTypeRegistryFacade::getInstance();
        $customPostTypeNames = [];
        foreach ($customPostTypeRegistry->getCustomPostTypes() as $customPostTypeService) {
            $customPostTypeObject = get_post_type_object($customPostTypeService->getCustomPostType());
            if ($customPostTypeObject === null) {
                continue;
            }
            $customPostTypeNames[] = esc_html($customPostTypeObject->labels->name);
        }
        $customPostTypeNames = array_values(array_unique($customPostTypeNames));

        if ($customPostTypeNames === []) {
            return \__('Remove the entries you created and edited through the plugin. Only applies when deleting all plugin data.', 'gatographql');
        }

        return sprintf(
            \__('Remove the entries you created and edited through the plugin: %s. Only applies when deleting all plugin data.', 'gatographql'),
            implode(\__(', ', 'gatographql'), $customPostTypeNames)
        );
    }

    protected function getGatoGraphQLShopName(): string
    {
        return \__('Gato Plugins Store', 'gatographql');
    }

    protected function getActivateExtensionLicensesTitle(): string
    {
        return \__('Activate Licenses', 'gatographql');
    }
}
