<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\Constants\ResetSettingsOptions;
use GatoGraphQL\GatoGraphQL\ContentProcessors\MarkdownContentParserInterface;
use GatoGraphQL\GatoGraphQL\Facades\Registries\CustomPostTypeRegistryFacade;
use GatoGraphQL\GatoGraphQL\Marketplace\Enums\LicenseStatus;
use GatoGraphQL\GatoGraphQL\Marketplace\ObjectModels\CommercialExtensionActivatedLicenseObjectProperties;
use GatoGraphQL\GatoGraphQL\ModuleSettings\Properties;
use GatoGraphQL\GatoGraphQL\Plugin;
use GatoGraphQL\GatoGraphQL\PluginApp;
use GatoGraphQL\GatoGraphQL\Registries\SettingsCategoryRegistryInterface;
use GatoGraphQL\GatoGraphQL\Services\MenuPages\SettingsMenuPage;
use GatoGraphQL\GatoGraphQL\SettingsCategoryResolvers\SettingsCategoryResolver;
use GatoGraphQL\GatoGraphQL\StaticHelpers\BehaviorHelpers;
use GatoGraphQL\GatoGraphQL\StaticHelpers\LicenseActionHelpers;
use GatoGraphQL\GatoGraphQL\StaticHelpers\SettingsHelpers;

use function array_keys;
use function esc_html;
use function function_exists;
use function get_post_type_object;
use function get_settings_errors;
use function get_submit_button;
use function in_array;
use function preg_replace;
use function sprintf;
use function str_starts_with;
use function strlen;
use function substr;
use function trim;
use function wp_strip_all_tags;

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

    /**
     * The code of the notice of a license operation (activate,
     * deactivate or validate), followed by the extension's slug
     */
    public final const LICENSE_OPERATION_NOTICE_CODE_PREFIX = 'license_activation_';

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
                $licenseActionsFieldName = sprintf(
                    '%s[%s]',
                    $this->getSettingsCategoryRegistry()->getSettingsCategoryResolver(SettingsCategoryResolver::PLUGIN_MANAGEMENT)->getOptionsFormName(SettingsCategoryResolver::PLUGIN_MANAGEMENT),
                    SettingsMenuPage::LICENSE_ACTION_BUTTON_ID
                );
                $option = self::OPTION_COMMERCIAL_EXTENSION_LICENSE_KEYS;
                $moduleSettings[] = [
                    Properties::INPUT => $option,
                    Properties::NAME => $this->getSettingOptionName(
                        $module,
                        $option
                    ),
                    Properties::TITLE => $activateExtensionLicensesTitle,
                    Properties::DESCRIPTION => sprintf(
                        \__('Enter each license key from the %s, then activate it.', 'gatographql'),
                        $this->getGatoGraphQLShopName()
                    ),
                    Properties::HELP => sprintf(
                        '<p>%s</p><ul><li>%s</li><li>%s</li></ul><p>%s</p>',
                        \__('The button beside each license acts on that license only:', 'gatographql'),
                        \__('<strong>Activate</strong> activates the key you entered, or the new key that replaces the previous one.', 'gatographql'),
                        \__('<strong>Validate</strong> checks the status of an active license, and <strong>Deactivate</strong> frees its site and removes its key (also when you clear the key).', 'gatographql'),
                        \__('The button below the licenses does all of it at once: it activates every key you entered, and deactivates every license whose key you cleared.', 'gatographql'),
                    ),
                    Properties::TYPE => Properties::TYPE_PROPERTY_ARRAY,
                    Properties::KEY_LABELS => $commercialExtensionSlugProductNames,
                    Properties::KEY_STATUSES => $this->getCommercialExtensionLicenseStatuses(array_keys($commercialExtensionSlugProductNames)),
                    Properties::KEY_ACTIONS => $this->getCommercialExtensionLicenseActions(array_keys($commercialExtensionSlugProductNames)),
                    Properties::KEY_ACTIONS_FIELD => $licenseActionsFieldName,
                    Properties::KEY_EDIT_ACTIONS => $this->getCommercialExtensionLicenseEditActions(array_keys($commercialExtensionSlugProductNames)),
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
                        \__('Apply license changes', 'gatographql'),
                        'secondary',
                        $activateButtonName,
                        false,
                        [
                            'data-entry-batch' => '',
                            'data-label-none' => \__('Activate licenses', 'gatographql'),
                            'data-label-changed-one' => \__('Activate 1 license', 'gatographql'),
                            /* translators: %s: number of licenses */
                            'data-label-changed-many' => \__('Activate %s licenses', 'gatographql'),
                            'data-label-cleared-one' => \__('Deactivate 1 license', 'gatographql'),
                            /* translators: %s: number of licenses */
                            'data-label-cleared-many' => \__('Deactivate %s licenses', 'gatographql'),
                            /* translators: %s: number of licenses */
                            'data-label-mixed' => \__('Apply %s license changes', 'gatographql'),
                        ]
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
                Properties::TITLE => \__('Reset the settings', 'gatographql'),
                Properties::DESCRIPTION => sprintf(
                    '<p>%s</p><p>%s</p>',
                    sprintf(
                        \__('Restore every setting under tabs <code>%s</code>, <code>%s</code>, <code>%s</code>, <code>%s</code> and <code>%s</code> to its default value.', 'gatographql'),
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
                    \__('You can reset to restrictive or non-restrictive defaults:', 'gatographql'),
                    \__('Feature', 'gatographql'),
                    \__('Non-restrictive', 'gatographql'),
                    \__('Restrictive', 'gatographql'),
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
                                    \__('In the schema', 'gatographql'),
                                    \__('Not in the schema', 'gatographql'),
                                ]
                            ) . '</td>',
                            '<td>' . implode(
                                '</td><td>',
                                [
                                    \__('Settings from <code>wp_options</code>', 'gatographql'),
                                    \__('Non-admins can query no options', 'gatographql'),
                                    \__('Non-admins can query a few predefined options', 'gatographql'),
                                ]
                            ) . '</td>',
                            '<td>' . implode(
                                '</td><td>',
                                [
                                    \__('Meta (posts, users, comments, taxonomies)', 'gatographql'),
                                    \__('Queries can read every key', 'gatographql'),
                                    \__('Queries can read no keys', 'gatographql'),
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
                                    \__('Queries can read every environment variable and PHP constant', 'gatographql'),
                                    \__('Queries can read no environment variables or PHP constants', 'gatographql'),
                                ]
                            ) . '</td>',
                            '<td>' . implode(
                                '</td><td>',
                                [
                                    \__('HTTP Client requests (extension)', 'gatographql'),
                                    \__('Queries can request any URL', 'gatographql'),
                                    \__('Queries can request no URL', 'gatographql'),
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
                    \__('The defaults to reset to.', 'gatographql'),
                ),
                Properties::TYPE => Properties::TYPE_STRING,
                Properties::POSSIBLE_VALUES => [
                    ResetSettingsOptions::RESTRICTIVE => \__('Restrictive defaults', 'gatographql'),
                    ResetSettingsOptions::NON_RESTRICTIVE => \__('Non-restrictive defaults', 'gatographql'),
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
                Properties::TITLE => \__('Delete all plugin data', 'gatographql'),
                Properties::DESCRIPTION => \__('Deleting the plugin also removes everything it stored on this site.', 'gatographql'),
                Properties::HELP => sprintf(
                    '%s<ul><li>%s</li></ul>%s',
                    \__('It removes:', 'gatographql'),
                    implode(
                        '</li><li>',
                        [
                            \__('Its settings', 'gatographql'),
                            \__('The license records of its extensions', 'gatographql'),
                            \__('The metadata it added to your content and users', 'gatographql'),
                            \__('Its database tables, and its cache and log files', 'gatographql'),
                        ]
                    ),
                    sprintf(
                        \__('Removing a license record does not release the license on the marketplace. To use it on another site, first click <strong>Deactivate</strong> beside it under <strong>%s</strong>.', 'gatographql'),
                        $this->getActivateExtensionLicensesTitle()
                    ),
                ),
                Properties::TYPE => Properties::TYPE_BOOL,
            ];

            $option = self::OPTION_DELETE_CONTENT_ON_UNINSTALL;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Also delete the plugin\'s entries', 'gatographql'),
                Properties::DESCRIPTION => $this->getDeleteContentOnUninstallDescription(),
                Properties::TYPE => Properties::TYPE_BOOL,
                Properties::DEPENDS_ON => [
                    self::OPTION_DELETE_DATA_ON_UNINSTALL => true,
                ],
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
            return \__('Also remove the entries you created and edited through the plugin.', 'gatographql');
        }

        return sprintf(
            '%s%s',
            \__('Also remove the entries you created and edited through the plugin.', 'gatographql'),
            $this->getCollapsible(
                sprintf(
                    \__('These entries: %s.', 'gatographql'),
                    implode(\__(', ', 'gatographql'), $customPostTypeNames)
                )
            )
        );
    }

    /**
     * The status of the license of each commercial extension, as
     * stored when it was last activated, deactivated or validated,
     * or the error the last attempt produced
     *
     * @param string[] $extensionSlugs
     * @return array<string,array{label:string,tone:string,detail?:string,detailTone?:string}>
     */
    protected function getCommercialExtensionLicenseStatuses(array $extensionSlugs): array
    {
        $activatedLicenses = SettingsHelpers::getCommercialExtensionActivatedLicenseObjectProperties();
        $failures = $this->getCommercialExtensionLicenseOperationFailures();
        $statuses = [];
        foreach ($extensionSlugs as $extensionSlug) {
            $activatedLicense = $activatedLicenses[$extensionSlug] ?? null;
            $status = $activatedLicense === null
                ? [
                    'label' => \__('Not activated', 'gatographql'),
                    'tone' => Properties::KEY_STATUS_TONE_NEUTRAL,
                ]
                : $this->getCommercialExtensionLicenseStatus($activatedLicense);
            $failure = $failures[$extensionSlug] ?? null;
            if ($failure !== null && $failure['type'] === 'error') {
                $status = [
                    'label' => \__('Error', 'gatographql'),
                    'tone' => Properties::KEY_STATUS_TONE_DANGER,
                ];
            }
            if ($failure !== null) {
                $status['detail'] = trim(wp_strip_all_tags((string) preg_replace('#<br\s*/?>#i', ' ', $failure['message'])));
                $status['detailTone'] = $failure['type'] === 'error'
                    ? Properties::KEY_STATUS_TONE_DANGER
                    : Properties::KEY_STATUS_TONE_WARNING;
            }
            $statuses[$extensionSlug] = $status;
        }
        return $statuses;
    }

    /**
     * The button beside each license: "Activate" when not active yet,
     * "Validate" and "Deactivate" once it is.
     *
     * @param string[] $extensionSlugs
     * @return array<string,array<string,string>>
     */
    protected function getCommercialExtensionLicenseActions(array $extensionSlugs): array
    {
        $activatedLicenses = SettingsHelpers::getCommercialExtensionActivatedLicenseObjectProperties();
        $actions = [];
        foreach ($extensionSlugs as $extensionSlug) {
            if (!isset($activatedLicenses[$extensionSlug])) {
                $actions[$extensionSlug] = [
                    LicenseActionHelpers::getActionValue(LicenseActionHelpers::ACTION_ACTIVATE, $extensionSlug) => \__('Activate', 'gatographql'),
                ];
                continue;
            }
            $actions[$extensionSlug] = [
                LicenseActionHelpers::getActionValue(LicenseActionHelpers::ACTION_VALIDATE, $extensionSlug) => \__('Validate', 'gatographql'),
                LicenseActionHelpers::getActionValue(LicenseActionHelpers::ACTION_DEACTIVATE, $extensionSlug) => \__('Deactivate', 'gatographql'),
            ];
        }
        return $actions;
    }

    /**
     * Once a license key is edited, its row offers the step to take: to
     * activate the key entered, or to deactivate the license whose key
     * was cleared.
     *
     * @param string[] $extensionSlugs
     * @return array<string,array<string,array<string,string>>>
     */
    protected function getCommercialExtensionLicenseEditActions(array $extensionSlugs): array
    {
        $editActions = [];
        foreach ($extensionSlugs as $extensionSlug) {
            $editActions[$extensionSlug] = [
                Properties::KEY_EDIT_CHANGED => [
                    LicenseActionHelpers::getActionValue(LicenseActionHelpers::ACTION_ACTIVATE, $extensionSlug) => \__('Activate', 'gatographql'),
                ],
                Properties::KEY_EDIT_CLEARED => [
                    LicenseActionHelpers::getActionValue(LicenseActionHelpers::ACTION_DEACTIVATE, $extensionSlug) => \__('Deactivate', 'gatographql'),
                ],
            ];
        }
        return $editActions;
    }

    /**
     * @return array{label:string,tone:string}
     */
    protected function getCommercialExtensionLicenseStatus(CommercialExtensionActivatedLicenseObjectProperties $activatedLicense): array
    {
        return match ($activatedLicense->status) {
            LicenseStatus::ACTIVE => [
                'label' => \__('Active', 'gatographql'),
                'tone' => Properties::KEY_STATUS_TONE_SUCCESS,
            ],
            LicenseStatus::EXPIRED => [
                'label' => \__('Expired', 'gatographql'),
                'tone' => Properties::KEY_STATUS_TONE_WARNING,
            ],
            LicenseStatus::DISABLED => [
                'label' => \__('Disabled', 'gatographql'),
                'tone' => Properties::KEY_STATUS_TONE_DANGER,
            ],
            LicenseStatus::UNREGISTERED => [
                'label' => \__('Not activated', 'gatographql'),
                'tone' => Properties::KEY_STATUS_TONE_NEUTRAL,
            ],
            LicenseStatus::INACTIVE,
            LicenseStatus::OTHER => [
                'label' => \__('Inactive', 'gatographql'),
                'tone' => Properties::KEY_STATUS_TONE_WARNING,
            ],
        };
    }

    /**
     * The errors (the license is not valid) and warnings (the license
     * server could not be reached) of the last attempt to activate,
     * deactivate or validate the licenses, by extension, back from
     * options.php
     *
     * @return array<string,array{type:string,message:string}>
     */
    protected function getCommercialExtensionLicenseOperationFailures(): array
    {
        if (!function_exists('get_settings_errors')) {
            return [];
        }
        $failures = [];
        foreach (get_settings_errors() as $settingsError) {
            $code = $settingsError['code'];
            $type = $settingsError['type'];
            if (!str_starts_with($code, self::LICENSE_OPERATION_NOTICE_CODE_PREFIX) || !in_array($type, ['error', 'warning'], true)) {
                continue;
            }
            $failures[substr($code, strlen(self::LICENSE_OPERATION_NOTICE_CODE_PREFIX))] = [
                'type' => $type,
                'message' => $settingsError['message'],
            ];
        }
        return $failures;
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
