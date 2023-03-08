<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\Constants\ResetSettingsOptions;
use GraphQLAPI\GraphQLAPI\ContentProcessors\MarkdownContentParserInterface;
use GraphQLAPI\GraphQLAPI\ModuleSettings\Properties;
use GraphQLAPI\GraphQLAPI\Plugin;
use GraphQLAPI\GraphQLAPI\PluginEnvironment;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\SettingsMenuPage;

use function get_submit_button;

class PluginManagementFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver
{
    use ModuleResolverTrait;
    use PluginManagementFunctionalityModuleResolverTrait;

    public final const PLUGIN_MANAGEMENT = Plugin::NAMESPACE . '\plugin-management';

    /**
     * Setting options
     */
    public final const OPTION_USE_SAFE_OR_UNSAFE_DEFAULT_BEHAVIOR = 'use-safe-or-unsafe-default-behavior';

    private ?MarkdownContentParserInterface $markdownContentParser = null;

    final public function setMarkdownContentParser(MarkdownContentParserInterface $markdownContentParser): void
    {
        $this->markdownContentParser = $markdownContentParser;
    }
    final protected function getMarkdownContentParser(): MarkdownContentParserInterface
    {
        /** @var MarkdownContentParserInterface */
        return $this->markdownContentParser ??= $this->instanceManager->getInstance(MarkdownContentParserInterface::class);
    }

    /**
     * @return string[]
     */
    public function getModulesToResolve(): array
    {
        return [
            self::PLUGIN_MANAGEMENT,
        ];
    }

    public function isPredefinedEnabledOrDisabled(string $module): ?bool
    {
        return match ($module) {
            self::PLUGIN_MANAGEMENT => true,
            default => parent::isPredefinedEnabledOrDisabled($module),
        };
    }

    public function isHidden(string $module): bool
    {
        return match ($module) {
            self::PLUGIN_MANAGEMENT => true,
            default => parent::isHidden($module),
        };
    }

    public function getName(string $module): string
    {
        return match ($module) {
            self::PLUGIN_MANAGEMENT => \__('Plugin Management', 'graphql-api'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        return match ($module) {
            self::PLUGIN_MANAGEMENT => \__('Admin tools to manage the GraphQL API plugin', 'graphql-api'),
            default => parent::getDescription($module),
        };
    }

    /**
     * Default value for an option set by the module
     */
    public function getSettingsDefaultValue(string $module, string $option): mixed
    {
        $defaultValues = [
            self::PLUGIN_MANAGEMENT => [
                self::OPTION_USE_SAFE_OR_UNSAFE_DEFAULT_BEHAVIOR => PluginEnvironment::areUnsafeDefaultsEnabled(false) ? ResetSettingsOptions::UNSAFE : ResetSettingsOptions::SAFE,
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
        if ($module === self::PLUGIN_MANAGEMENT) {
            $option = self::OPTION_USE_SAFE_OR_UNSAFE_DEFAULT_BEHAVIOR;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Use "safe" or "unsafe" default behavior for Settings', 'graphql-api'),
                Properties::DESCRIPTION => sprintf(
                    '<p>%s</p><br/><p>%s</p><ul><li>%s</li></ul>',
                    \__('Define if to use the "safe" or "unsafe" default behavior for the Settings.', 'graphql-api'),
                    \__('<em>Explanation:</em> When the Settings values have not been configured yet, the plugin uses default values. These can have one of of two behaviors, "safe" or "unsafe":', 'graphql-api'),
                    implode(
                        '<br/></li><li>',
                        [
                            sprintf(
                                '<p>%s</p><ul><li>%s</li></ul>',
                                \__('<strong>Safe default settings</strong>, recommended to make a "live" site secure:', 'graphql-api'),
                                implode(
                                    '</li><li>',
                                    [
                                        \__('The single endpoint is disabled', 'graphql-api'),
                                        \__('The “sensitive” data elements (eg: input field <code>status</code> to query posts with status <code>"draft"</code>) are not added to the schema', 'graphql-api'),
                                        \__('Only a few of settings options and meta keys (for posts, users, etc) can be queried', 'graphql-api'),
                                        \__('The number of entities (for posts, users, etc) that can be queried at once is limited', 'graphql-api'),
                                    ]
                                )
                            ),
                            sprintf(
                                '<p>%s</p><ul><li>%s</li></ul>',
                                \__('<strong>Unsafe default settings</strong>, recommended when building "static" sites, where the WordPress site is not exposed to the Internet:', 'graphql-api'),
                                implode(
                                    '</li><li>',
                                    [
                                        \__('The single endpoint is enabled', 'graphql-api'),
                                        \__('The “sensitive” data elements are exposed in the schema', 'graphql-api'),
                                        \__('All settings options and meta keys can be queried', 'graphql-api'),
                                        \__('The number of entities that can be queried at once is unlimited', 'graphql-api'),
                                    ]
                                )
                            )
                        ]
                    ),
                ),
                Properties::TYPE => Properties::TYPE_STRING,
                Properties::POSSIBLE_VALUES => [
                    ResetSettingsOptions::SAFE => \__('Use "safe" default values', 'graphql-api'),
                    ResetSettingsOptions::UNSAFE => \__('Use "unsafe" default values', 'graphql-api'),
                ],
            ];
            
            $resetSettingsButtonsHTML = '';
            /**
             * Use `function_exists` because, when pressing on
             * the button it will call options.php,
             * and the function will not have been loaded yet!
             */
            if (function_exists('get_submit_button')) {
                /**
                 * Have the reset button name be sent as part of the form
                 */
                $resetButtonName = sprintf(
                    '%s[%s]',
                    SettingsMenuPage::SETTINGS_FIELD,
                    SettingsMenuPage::RESET_SETTINGS_BUTTON_ID
                );
                $buttonWrapperID = SettingsMenuPage::RESET_SETTINGS_BUTTON_ID . '-wrapper';
                $resetSettingsButtonsHTML = sprintf(
                    '<p class="submit"><a href="#" onclick="document.getElementById(\'%s\').style.display=\'block\';return false;" class="button secondary">%s</a></p>',
                    $buttonWrapperID,
                    \__('Reset Settings', 'graphql-api')
                );
                $resetSettingsButtonsHTML .= sprintf(
                    '<p id="%s" style="display: none;">%s</p>',
                    $buttonWrapperID,
                    get_submit_button(
                        \__('Please confirm: Reset Settings', 'graphql-api'),
                        'primary',
                        $resetButtonName,
                        false
                    )
                );
            }
            $moduleSettings[] = [
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    'reset-settings'
                ),
                Properties::TITLE => \__('Reset the Settings?', 'graphql-api'),
                Properties::DESCRIPTION => sprintf(
                    '<p>%s</p>%s',
                    \__('Reset the Settings for all modules; then, the values defined by either the "safe" or "unsafe" default behavior will be used (see above).', 'graphql-api'),
                    $resetSettingsButtonsHTML
                ),
                Properties::TYPE => Properties::TYPE_NULL,
            ];
        }
        return $moduleSettings;
    }
}
