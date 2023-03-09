<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\ContentProcessors\MarkdownContentParserInterface;
use GraphQLAPI\GraphQLAPI\ModuleSettings\Properties;
use GraphQLAPI\GraphQLAPI\Plugin;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\SettingsMenuPage;

use function get_submit_button;

class PluginManagementFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver
{
    use ModuleResolverTrait;
    use PluginManagementFunctionalityModuleResolverTrait;

    public final const PLUGIN_MANAGEMENT = Plugin::NAMESPACE . '\plugin-management';

    private ?MarkdownContentParserInterface $markdownContentParser = null;
    private ?PluginGeneralSettingsFunctionalityModuleResolver $pluginGeneralSettingsFunctionalityModuleResolver = null;

    final public function setMarkdownContentParser(MarkdownContentParserInterface $markdownContentParser): void
    {
        $this->markdownContentParser = $markdownContentParser;
    }
    final protected function getMarkdownContentParser(): MarkdownContentParserInterface
    {
        /** @var MarkdownContentParserInterface */
        return $this->markdownContentParser ??= $this->instanceManager->getInstance(MarkdownContentParserInterface::class);
    }
    final public function setPluginGeneralSettingsFunctionalityModuleResolver(PluginGeneralSettingsFunctionalityModuleResolver $pluginGeneralSettingsFunctionalityModuleResolver): void
    {
        $this->pluginGeneralSettingsFunctionalityModuleResolver = $pluginGeneralSettingsFunctionalityModuleResolver;
    }
    final protected function getPluginGeneralSettingsFunctionalityModuleResolver(): PluginGeneralSettingsFunctionalityModuleResolver
    {
        /** @var PluginGeneralSettingsFunctionalityModuleResolver */
        return $this->pluginGeneralSettingsFunctionalityModuleResolver ??= $this->instanceManager->getInstance(PluginGeneralSettingsFunctionalityModuleResolver::class);
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
     * Array with the inputs to show as settings for the module
     *
     * @return array<array<string,mixed>> List of settings for the module, each entry is an array with property => value
     */
    public function getSettings(string $module): array
    {
        $moduleSettings = parent::getSettings($module);
        if ($module === self::PLUGIN_MANAGEMENT) {
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
                    sprintf(
                        \__('Delete all stored Settings values. Then, the "safe" or "unsafe" default behavior will be be applied (see tab "%s").', 'graphql-api'),
                        $this->getPluginGeneralSettingsFunctionalityModuleResolver()->getName(
                            PluginGeneralSettingsFunctionalityModuleResolver::GENERAL
                        )
                    ),
                    $resetSettingsButtonsHTML
                ),
                Properties::TYPE => Properties::TYPE_NULL,
            ];
        }
        return $moduleSettings;
    }
}
