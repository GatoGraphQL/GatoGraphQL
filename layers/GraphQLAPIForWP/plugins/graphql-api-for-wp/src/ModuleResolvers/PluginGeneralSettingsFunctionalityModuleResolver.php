<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\Constants\ResetSettingsOptions;
use GraphQLAPI\GraphQLAPI\ContentProcessors\MarkdownContentParserInterface;
use GraphQLAPI\GraphQLAPI\Module;
use GraphQLAPI\GraphQLAPI\ModuleConfiguration;
use GraphQLAPI\GraphQLAPI\ModuleSettings\Properties;
use GraphQLAPI\GraphQLAPI\Plugin;
use GraphQLAPI\GraphQLAPI\PluginEnvironment;
use PoP\ComponentModel\App;
use PoP\Root\Environment as RootEnvironment;

class PluginGeneralSettingsFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver
{
    use ModuleResolverTrait;
    use PluginGeneralSettingsFunctionalityModuleResolverTrait;

    public final const GENERAL = Plugin::NAMESPACE . '\general';

    /**
     * Setting options
     */
    public final const OPTION_ADD_RELEASE_NOTES_ADMIN_NOTICE = 'add-release-notes-admin-notice';
    public final const OPTION_PRINT_SETTINGS_WITH_TABS = 'print-settings-with-tabs';
    public final const OPTION_USE_SAFE_OR_UNSAFE_DEFAULT_BEHAVIOR = 'use-safe-or-unsafe-default-behavior';
    public final const OPTION_CLIENT_IP_ADDRESS_SERVER_PROPERTY_NAME = 'client-ip-address-server-property-name';

    private ?MarkdownContentParserInterface $markdownContentParser = null;
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
            self::GENERAL,
        ];
    }

    public function isPredefinedEnabledOrDisabled(string $module): ?bool
    {
        return match ($module) {
            self::GENERAL => true,
            default => parent::isPredefinedEnabledOrDisabled($module),
        };
    }

    public function isHidden(string $module): bool
    {
        return match ($module) {
            self::GENERAL => true,
            default => parent::isHidden($module),
        };
    }

    public function getName(string $module): string
    {
        return match ($module) {
            self::GENERAL => \__('General', 'graphql-api'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        return match ($module) {
            self::GENERAL => \__('General options for the plugin', 'graphql-api'),
            default => parent::getDescription($module),
        };
    }

    /**
     * Default value for an option set by the module
     */
    public function getSettingsDefaultValue(string $module, string $option): mixed
    {
        $useUnsafeDefaults = PluginEnvironment::getDefinedEnableUnsafeDefaults() ?? RootEnvironment::isApplicationEnvironmentDev();
        $defaultValues = [
            self::GENERAL => [
                self::OPTION_ADD_RELEASE_NOTES_ADMIN_NOTICE => true,
                self::OPTION_PRINT_SETTINGS_WITH_TABS => true,
                self::OPTION_USE_SAFE_OR_UNSAFE_DEFAULT_BEHAVIOR => $useUnsafeDefaults ? ResetSettingsOptions::UNSAFE : ResetSettingsOptions::SAFE,
                self::OPTION_CLIENT_IP_ADDRESS_SERVER_PROPERTY_NAME => 'REMOTE_ADDR',
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
        if ($module === self::GENERAL) {
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
            $option = self::OPTION_USE_SAFE_OR_UNSAFE_DEFAULT_BEHAVIOR;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Use "safe" or "unsafe" default behavior for Settings', 'graphql-api'),
                Properties::DESCRIPTION => sprintf(
                    '<p>%s</p><br/><p>%s</p><br/><p>%s</p><p>%s</p><ul><li>%s</li></ul><br/><p>%s</p><p>%s</p><ul><li>%s</li></ul>',
                    sprintf(
                        \__('<strong>Please notice:</strong> after storing the new value (i.e. after submitting "Save Changes (All)"), you will need to go to the "%s" tab, and click on the "Reset Settings" button to have the new default behavior be applied.', 'graphql-api'),
                        $this->getPluginManagementFunctionalityModuleResolver()->getName(PluginManagementFunctionalityModuleResolver::PLUGIN_MANAGEMENT)
                    ),
                    \__('Before values in the Settings page are configured, the plugin uses default values, which can have a "safe" or "unsafe" behavior.', 'graphql-api'),
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
                Properties::TYPE => Properties::TYPE_STRING,
                Properties::POSSIBLE_VALUES => [
                    ResetSettingsOptions::SAFE => \__('Use "safe" default behavior', 'graphql-api'),
                    ResetSettingsOptions::UNSAFE => \__('Use "unsafe" default behavior', 'graphql-api'),
                ],
            ];

            // If any extension depends on this, it shall enable it
            /** @var ModuleConfiguration */
            $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
            if ($moduleConfiguration->enableSettingClientIPAddressServerPropertyName()) {
                $moduleSettings[] = [
                    Properties::NAME => $this->getSettingOptionName(
                        $module,
                        'separator'
                    ),
                    Properties::DESCRIPTION => sprintf(
                        __('<hr/>', 'graphql-api'),
                    ),
                    Properties::TYPE => Properties::TYPE_NULL,
                ];
                $moduleSettings[] = [
                    Properties::NAME => $this->getSettingOptionName(
                        $module,
                        'intro'
                    ),
                    Properties::TITLE => \__('Server Configuration', 'graphql-api'),
                    Properties::DESCRIPTION => sprintf(
                        __('The following options configure the plugin according to the platform/environment under which it is running.', 'graphql-api'),
                    ),
                    Properties::TYPE => Properties::TYPE_NULL,
                ];

                $option = self::OPTION_CLIENT_IP_ADDRESS_SERVER_PROPERTY_NAME;
                $moduleSettings[] = [
                    Properties::INPUT => $option,
                    Properties::NAME => $this->getSettingOptionName(
                        $module,
                        $option
                    ),
                    Properties::TITLE => \__('$_SERVER property name to retrieve the client IP', 'graphql-api'),
                    Properties::DESCRIPTION => sprintf(
                        '%s<br/><br/>%s<ul>%s</ul>',
                        \__('The visitor\'s IP address is retrieved from under the <code>$_SERVER</code> global variable, by default under property <code>\'REMOTE_ADDR\'</code>; depending on the platform or hosting provider, a different property may need to be used.', 'graphql-api'),
                        \__('For instance:', 'graphql-api'),
                        '<li>' . implode(
                            '</li><li>',
                            [
                                \__('Cloudflare might use <code>\'HTTP_CF_CONNECTING_IP\'</code>', 'graphql-api'),
                                \__('AWS might use <code>\'HTTP_X_FORWARDED_FOR\'</code>', 'graphql-api'),
                                \__('others', 'graphql-api'),
                            ]
                        ) . '</li>'
                    ),
                    Properties::TYPE => Properties::TYPE_STRING,
                ];
            }
        }
        return $moduleSettings;
    }
}
