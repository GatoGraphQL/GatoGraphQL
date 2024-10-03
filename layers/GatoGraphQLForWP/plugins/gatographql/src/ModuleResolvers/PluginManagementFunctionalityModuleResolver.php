<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\Constants\ResetSettingsOptions;
use GatoGraphQL\GatoGraphQL\ContentProcessors\MarkdownContentParserInterface;
use GatoGraphQL\GatoGraphQL\ModuleSettings\Properties;
use GatoGraphQL\GatoGraphQL\Plugin;
use GatoGraphQL\GatoGraphQL\PluginApp;
use GatoGraphQL\GatoGraphQL\Registries\SettingsCategoryRegistryInterface;
use GatoGraphQL\GatoGraphQL\Services\MenuPages\SettingsMenuPage;
use GatoGraphQL\GatoGraphQL\SettingsCategoryResolvers\SettingsCategoryResolver;
use GatoGraphQL\GatoGraphQL\StaticHelpers\BehaviorHelpers;

use function get_submit_button;

class PluginManagementFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver
{
    use ModuleResolverTrait;
    use PluginManagementFunctionalityModuleResolverTrait;

    public final const ACTIVATE_EXTENSIONS = Plugin::NAMESPACE . '\activate-extensions';
    public final const RESET_SETTINGS = Plugin::NAMESPACE . '\reset-settings';

    /**
     * Setting options
     */
    public final const OPTION_COMMERCIAL_EXTENSION_LICENSE_KEYS = 'commercial-extension-license-keys';
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
        if ($this->markdownContentParser === null) {
            /** @var MarkdownContentParserInterface */
            $markdownContentParser = $this->instanceManager->getInstance(MarkdownContentParserInterface::class);
            $this->markdownContentParser = $markdownContentParser;
        }
        return $this->markdownContentParser;
    }
    final public function setSettingsCategoryRegistry(SettingsCategoryRegistryInterface $settingsCategoryRegistry): void
    {
        $this->settingsCategoryRegistry = $settingsCategoryRegistry;
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
    final public function setPluginManagementFunctionalityModuleResolver(PluginManagementFunctionalityModuleResolver $pluginManagementFunctionalityModuleResolver): void
    {
        $this->pluginManagementFunctionalityModuleResolver = $pluginManagementFunctionalityModuleResolver;
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
        ];
    }

    public function isPredefinedEnabledOrDisabled(string $module): ?bool
    {
        return match ($module) {
            self::ACTIVATE_EXTENSIONS,
            self::RESET_SETTINGS
                => true,
            default
                => parent::isPredefinedEnabledOrDisabled($module),
        };
    }

    public function isHidden(string $module): bool
    {
        return match ($module) {
            self::ACTIVATE_EXTENSIONS,
            self::RESET_SETTINGS
                => true,
            default
                => parent::isHidden($module),
        };
    }

    public function getName(string $module): string
    {
        return match ($module) {
            self::ACTIVATE_EXTENSIONS => \__('Activate Extensions', 'gatographql'),
            self::RESET_SETTINGS => \__('Reset Settings', 'gatographql'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        return match ($module) {
            self::ACTIVATE_EXTENSIONS => \__('Activate Bundles and Extensions from the Gato GraphQL Shop', 'gatographql'),
            self::RESET_SETTINGS => \__('Restore the Gato GraphQL Settings to default values', 'gatographql'),
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
            if ($commercialExtensionSlugProductNames !== []) {
                $ulPlaceholder = '<ul><li>%s</li></ul>';
                $handlingLicenseMessageItems = [
                    \__('Adding a license key will <strong>activate the product</strong>', 'gatographql'),
                    \__('Removing an existing license key will <strong>deactivate the product</strong>', 'gatographql'),
                    \__('Updating a license key will first <strong>deactivate the product</strong> (using the previous license key) and then <strong>activate the product</strong> again (using the new license key)', 'gatographql'),
                    \__('Not updating a license key will <strong>validate the status of the product</strong>', 'gatographql'),
                ];
                $option = self::OPTION_COMMERCIAL_EXTENSION_LICENSE_KEYS;
                $moduleSettings[] = [
                    Properties::INPUT => $option,
                    Properties::NAME => $this->getSettingOptionName(
                        $module,
                        $option
                    ),
                    Properties::TITLE => \__('Activate Extension Licenses', 'gatographql'),
                    Properties::DESCRIPTION => sprintf(
                        '%s<br/><br/>%s',
                        \__('Enter the license keys for the products purchased on the Gato GraphQL Shop, and click on <strong>Activate Licenses (or Deactivate/Validate)</strong>:', 'gatographql'),
                        $this->getCollapsible(
                            sprintf(
                                '%s%s',
                                \__('When clicking on <strong>Activate Licenses (or Deactivate/Validate)</strong>, one of the following actions will take place:'),
                                sprintf(
                                    $ulPlaceholder,
                                    implode(
                                        '</li><li>',
                                        $handlingLicenseMessageItems
                                    )
                                ),
                            ),
                            \__('(Show details: When are products activated, deactivated or validated?)')
                        ),
                    ),
                    Properties::TYPE => Properties::TYPE_PROPERTY_ARRAY,
                    Properties::KEY_LABELS => $commercialExtensionSlugProductNames,
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
                        \__('Activate Licenses (or Deactivate/Validate)', 'gatographql'),
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
                    Properties::TITLE => \__('Activate Extension Licenses', 'gatographql'),
                    Properties::DESCRIPTION => \__('<em>There are no Bundles or Extensions from the Gato GraphQL Shop installed</em>', 'gatographql'),
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
                                    \__('All options are queryable', 'gatographql'),
                                    \__('Only a few predefined options are queryable', 'gatographql'),
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
                                    \__('Environment Fields (by extension)', 'gatographql'),
                                    \__('All environment variables and PHP constants are queryable', 'gatographql'),
                                    \__('No environment variables or PHP constants are queryable', 'gatographql'),
                                ]
                            ) . '</td>',
                            '<td>' . implode(
                                '</td><td>',
                                [
                                    \__('Send HTTP Requests (by extension)', 'gatographql'),
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
        }
        return $moduleSettings;
    }
}
