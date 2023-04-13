<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\Constants\AdminRequestParams;
use GraphQLAPI\GraphQLAPI\Constants\HTMLCodes;
use GraphQLAPI\GraphQLAPI\ContentProcessors\MarkdownContentParserInterface;
use GraphQLAPI\GraphQLAPI\Module;
use GraphQLAPI\GraphQLAPI\ModuleConfiguration;
use GraphQLAPI\GraphQLAPI\ModuleSettings\Properties;
use GraphQLAPI\GraphQLAPI\Plugin;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\ModulesMenuPage;
use PoP\ComponentModel\App;

class PluginGeneralSettingsFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver
{
    use ModuleResolverTrait;
    use PluginGeneralSettingsFunctionalityModuleResolverTrait;

    public final const GENERAL = Plugin::NAMESPACE . '\general';
    public final const PRIVATE_ENDPOINTS = Plugin::NAMESPACE . '\private-endpoints';
    public final const SERVER_IP_CONFIGURATION = Plugin::NAMESPACE . '\server-ip-configuration';

    /**
     * Setting options
     */
    public final const OPTION_ADD_RELEASE_NOTES_ADMIN_NOTICE = 'add-release-notes-admin-notice';
    public final const OPTION_PRINT_SETTINGS_WITH_TABS = 'print-settings-with-tabs';
    public final const OPTION_DISABLE_SCHEMA_MODULES_IN_PRIVATE_ENDPOINTS = 'disable-schema-modules-in-private-endpoints';
    public final const OPTION_CLIENT_IP_ADDRESS_SERVER_PROPERTY_NAME = 'client-ip-address-server-property-name';

    private ?MarkdownContentParserInterface $markdownContentParser = null;
    private ?ModulesMenuPage $modulesMenuPage = null;

    final public function setMarkdownContentParser(MarkdownContentParserInterface $markdownContentParser): void
    {
        $this->markdownContentParser = $markdownContentParser;
    }
    final protected function getMarkdownContentParser(): MarkdownContentParserInterface
    {
        /** @var MarkdownContentParserInterface */
        return $this->markdownContentParser ??= $this->instanceManager->getInstance(MarkdownContentParserInterface::class);
    }
    final public function setModulesMenuPage(ModulesMenuPage $modulesMenuPage): void
    {
        $this->modulesMenuPage = $modulesMenuPage;
    }
    final protected function getModulesMenuPage(): ModulesMenuPage
    {
        /** @var ModulesMenuPage */
        return $this->modulesMenuPage ??= $this->instanceManager->getInstance(ModulesMenuPage::class);
    }

    /**
     * @return string[]
     */
    public function getModulesToResolve(): array
    {
        return [
            self::GENERAL,
            self::PRIVATE_ENDPOINTS,
            self::SERVER_IP_CONFIGURATION,
        ];
    }

    public function isPredefinedEnabledOrDisabled(string $module): ?bool
    {
        return match ($module) {
            self::GENERAL,
            self::PRIVATE_ENDPOINTS,
            self::SERVER_IP_CONFIGURATION
                => true,
            default => parent::isPredefinedEnabledOrDisabled($module),
        };
    }

    public function isHidden(string $module): bool
    {
        return match ($module) {
            self::GENERAL,
            self::PRIVATE_ENDPOINTS,
            self::SERVER_IP_CONFIGURATION
                => true,
            default => parent::isHidden($module),
        };
    }

    public function getName(string $module): string
    {
        return match ($module) {
            self::GENERAL => \__('General', 'graphql-api'),
            self::PRIVATE_ENDPOINTS => \__('Private Endpoints', 'graphql-api'),
            self::SERVER_IP_CONFIGURATION => \__('Server IP Configuration', 'graphql-api'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        return match ($module) {
            self::GENERAL => \__('General options for the plugin', 'graphql-api'),
            self::PRIVATE_ENDPOINTS => \__('Configure behavior for private endpoints in the GraphQL server', 'graphql-api'),
            self::SERVER_IP_CONFIGURATION => \__('Configure retrieving the Client IP depending on the platform/environment', 'graphql-api'),
            default => parent::getDescription($module),
        };
    }

    /**
     * Default value for an option set by the module
     */
    public function getSettingsDefaultValue(string $module, string $option): mixed
    {
        $defaultValues = [
            self::GENERAL => [
                self::OPTION_ADD_RELEASE_NOTES_ADMIN_NOTICE => true,
                self::OPTION_PRINT_SETTINGS_WITH_TABS => true,
            ],
            self::PRIVATE_ENDPOINTS => [
                self::OPTION_DISABLE_SCHEMA_MODULES_IN_PRIVATE_ENDPOINTS => false,
            ],
            self::SERVER_IP_CONFIGURATION => [
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
        } elseif ($module === self::PRIVATE_ENDPOINTS) {
            $option = self::OPTION_DISABLE_SCHEMA_MODULES_IN_PRIVATE_ENDPOINTS;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Disable "Schema modules" in the private endpoints?', 'graphql-api'),
                Properties::DESCRIPTION => \__('Indicate if disabled "Schema modules" must also be disabled on the private endpoints (in addition to the public endpoints)', 'graphql-api'),
                Properties::TYPE => Properties::TYPE_BOOL,
            ];

            $modulesMenuPage = $this->getModulesMenuPage();
            $moduleListURL = \admin_url(sprintf(
                'admin.php?page=%s&%s=%s',
                $modulesMenuPage->getScreenID(),
                AdminRequestParams::MODULE_TYPE,
                implode(',', [
                    'schema-directive',
                    'schema-type'
                ])
            ));
            $moduleSettings[] = [
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    'disable-schema-modules-in-private-endpoints-explanation'
                ),
                Properties::DESCRIPTION => sprintf(
                    '<strong>%s</strong><br/><br/>%s<br/><br/>%s<br/><br/>%s%s%s<br/><br/>%s%s%s',
                    \__('Explanation:'),
                    sprintf(
                        \__('We can <a href="%s" target="_blank">disable modules in the GraphQL API for WordPress%s</a>, to either remove some functionality from the GraphQL server (eg: the single endpoint), or remove some element from the GraphQL schema (eg: a type, a field, or a directive).', 'graphql-api'),
                        'https://graphql-api.com/guides/config/browsing-enabling-and-disabling-modules/#heading-enabling/disabling-a-module',
                        HTMLCodes::OPEN_IN_NEW_WINDOW
                    ),
                    sprintf(
                        \__('In the latter case, "Schema modules" are those modules that add types/fields/directives to the GraphQL schema, to be found under <a href="%s" target="_blank">categories "Schema Type" and "Schema Directive" in the Modules page%s</a>. For instance, when disabling the "Users" module, the user fields (<code>QueryRoot.users</code>, <code>Post.author</code>, and others) will be removed from the GraphQL schema.', 'graphql-api'),
                        $moduleListURL,
                        HTMLCodes::OPEN_IN_NEW_WINDOW
                    ),
                    \__('Disabling modules increases the security of the GraphQL API, as data that must not be consumed is never exposed in first place. As such, disabling endpoints always takes effect in public endpoints:', 'graphql-api'),
                    $this->getPublicEndpointsListDescription(),
                    \__('However, we may only want to limit access to the "Schema modules" to visitors (i.e. on public endpoints), but not to the admin of the site on private endpoints, as to not restrict the ability to access the full GraphQL schema for executing internal tasks.', 'graphql-api'),
                    \__('The private endpoints are:', 'graphql-api'),
                    $this->getPrivateEndpointsListDescription(),
                    \__('Should "Schema modules" also be disabled on the private endpoints?', 'graphql-api'),
                ),
                Properties::TYPE => Properties::TYPE_NULL,
            ];
        } elseif ($module === self::SERVER_IP_CONFIGURATION) {
            // If any extension depends on this, it shall enable it
            /** @var ModuleConfiguration */
            $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
            if ($moduleConfiguration->enableSettingClientIPAddressServerPropertyName()) {
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
