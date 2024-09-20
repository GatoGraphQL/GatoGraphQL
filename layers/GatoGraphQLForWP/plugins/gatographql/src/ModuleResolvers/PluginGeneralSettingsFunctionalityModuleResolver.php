<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\Constants\HTMLCodes;
use GatoGraphQL\GatoGraphQL\ContentProcessors\MarkdownContentParserInterface;
use GatoGraphQL\GatoGraphQL\Log\LoggerFiles;
use GatoGraphQL\GatoGraphQL\Module;
use GatoGraphQL\GatoGraphQL\ModuleConfiguration;
use GatoGraphQL\GatoGraphQL\ModuleSettings\Properties;
use GatoGraphQL\GatoGraphQL\Plugin;
use GatoGraphQL\GatoGraphQL\PluginEnvironment;
use GatoGraphQL\GatoGraphQL\PluginStaticModuleConfiguration;
use GatoGraphQL\GatoGraphQL\Registries\UserAuthorizationSchemeRegistryInterface;
use GatoGraphQL\GatoGraphQL\Services\MenuPages\ModulesMenuPage;
use PoP\ComponentModel\App;

class PluginGeneralSettingsFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver
{
    use ModuleResolverTrait;
    use PluginGeneralSettingsFunctionalityModuleResolverTrait;

    public final const GENERAL = Plugin::NAMESPACE . '\general';
    public final const SERVER_IP_CONFIGURATION = Plugin::NAMESPACE . '\server-ip-configuration';
    public final const SCHEMA_EDITING_ACCESS = Plugin::NAMESPACE . '\schema-editing-access';

    /**
     * Setting options
     */
    public final const OPTION_ENABLE_SCHEMA_TUTORIAL = 'hide-tutorial-page';
    public final const OPTION_ENABLE_LOGS = 'enable-logs';
    public final const OPTION_INSTALL_PLUGIN_SETUP_DATA = 'install-plugin-setup-data';
    public final const OPTION_ADD_RELEASE_NOTES_ADMIN_NOTICE = 'add-release-notes-admin-notice';
    public final const OPTION_PRINT_SETTINGS_WITH_TABS = 'print-settings-with-tabs';
    public final const OPTION_CLIENT_IP_ADDRESS_SERVER_PROPERTY_NAME = 'client-ip-address-server-property-name';
    public final const OPTION_EDITING_ACCESS_SCHEME = 'editing-access-scheme';

    private ?MarkdownContentParserInterface $markdownContentParser = null;
    private ?ModulesMenuPage $modulesMenuPage = null;
    private ?UserAuthorizationSchemeRegistryInterface $userAuthorizationSchemeRegistry = null;

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
    final public function setModulesMenuPage(ModulesMenuPage $modulesMenuPage): void
    {
        $this->modulesMenuPage = $modulesMenuPage;
    }
    final protected function getModulesMenuPage(): ModulesMenuPage
    {
        if ($this->modulesMenuPage === null) {
            /** @var ModulesMenuPage */
            $modulesMenuPage = $this->instanceManager->getInstance(ModulesMenuPage::class);
            $this->modulesMenuPage = $modulesMenuPage;
        }
        return $this->modulesMenuPage;
    }
    final public function setUserAuthorizationSchemeRegistry(UserAuthorizationSchemeRegistryInterface $userAuthorizationSchemeRegistry): void
    {
        $this->userAuthorizationSchemeRegistry = $userAuthorizationSchemeRegistry;
    }
    final protected function getUserAuthorizationSchemeRegistry(): UserAuthorizationSchemeRegistryInterface
    {
        if ($this->userAuthorizationSchemeRegistry === null) {
            /** @var UserAuthorizationSchemeRegistryInterface */
            $userAuthorizationSchemeRegistry = $this->instanceManager->getInstance(UserAuthorizationSchemeRegistryInterface::class);
            $this->userAuthorizationSchemeRegistry = $userAuthorizationSchemeRegistry;
        }
        return $this->userAuthorizationSchemeRegistry;
    }

    /**
     * @return string[]
     */
    public function getModulesToResolve(): array
    {
        return [
            self::GENERAL,
            self::SERVER_IP_CONFIGURATION,
            self::SCHEMA_EDITING_ACCESS,
        ];
    }

    public function isPredefinedEnabledOrDisabled(string $module): ?bool
    {
        return match ($module) {
            self::GENERAL,
            self::SERVER_IP_CONFIGURATION
                => true,
            default => parent::isPredefinedEnabledOrDisabled($module),
        };
    }

    public function isHidden(string $module): bool
    {
        return match ($module) {
            self::GENERAL,
            self::SERVER_IP_CONFIGURATION
                => true,
            default => parent::isHidden($module),
        };
    }

    public function getName(string $module): string
    {
        return match ($module) {
            self::GENERAL => \__('General', 'gatographql'),
            self::SERVER_IP_CONFIGURATION => \__('Server IP Configuration', 'gatographql'),
            self::SCHEMA_EDITING_ACCESS => \__('Schema Editing Access', 'gatographql-schema-editing-access'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        return match ($module) {
            self::GENERAL => \__('General options for the plugin', 'gatographql'),
            self::SERVER_IP_CONFIGURATION => \__('Configure retrieving the Client IP depending on the platform/environment', 'gatographql'),
            self::SCHEMA_EDITING_ACCESS => \__('Grant access to users other than admins to edit the GraphQL schema', 'gatographql-schema-editing-access'),
            default => parent::getDescription($module),
        };
    }

    /**
     * Default value for an option set by the module
     */
    public function getSettingsDefaultValue(string $module, string $option): mixed
    {
        if ($module === self::SCHEMA_EDITING_ACCESS && $option === self::OPTION_EDITING_ACCESS_SCHEME) {
            $defaultUserAuthorizationScheme = $this->getUserAuthorizationSchemeRegistry()->getDefaultUserAuthorizationScheme();
            return $defaultUserAuthorizationScheme->getName();
        }

        $defaultValues = [
            self::GENERAL => [
                self::OPTION_ENABLE_SCHEMA_TUTORIAL => false,
                self::OPTION_ENABLE_LOGS => false,
                self::OPTION_INSTALL_PLUGIN_SETUP_DATA => true,
                self::OPTION_ADD_RELEASE_NOTES_ADMIN_NOTICE => true,
                self::OPTION_PRINT_SETTINGS_WITH_TABS => true,
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
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();

        $moduleSettings = parent::getSettings($module);
        $moreInfoLabelPlaceholder = '<a href="%1$s" title="' . \__('\'%2$s\' on gatographql.com', 'gatographql') . '" target="_blank">' . \__('Help', 'gatographql') . HTMLCodes::OPEN_IN_NEW_WINDOW . '</a>';
        if ($module === self::GENERAL) {
            $option = self::OPTION_ENABLE_SCHEMA_TUTORIAL;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Enable the Schema tutorial?', 'gatographql'),
                Properties::DESCRIPTION => sprintf(
                    \__('Add a tutorial page explaining all elements of the GraphQL schema offered by Gato GraphQL, accessible from the menu navigation on the left<br/><span class="more-info">%s</span>', 'gatographql'),
                    sprintf(
                        $moreInfoLabelPlaceholder,
                        'https://gatographql.com/guides/config/enabling-the-schema-tutorial-page',
                        \__('Enabling the Schema Tutorial page', 'gatographql')
                    )
                ),
                Properties::TYPE => Properties::TYPE_BOOL,
            ];

            if ($moduleConfiguration->displayEnableLogsSettingsOption()) {
                $logFile = PluginEnvironment::getLogsFilePath(LoggerFiles::INFO);
                $relativeLogFile = str_replace(
                    constant('ABSPATH'),
                    '',
                    $logFile
                );
                $option = self::OPTION_ENABLE_LOGS;
                $moduleSettings[] = [
                    Properties::INPUT => $option,
                    Properties::NAME => $this->getSettingOptionName(
                        $module,
                        $option
                    ),
                    Properties::TITLE => \__('Enable Logs?', 'gatographql'),
                    Properties::DESCRIPTION => sprintf(
                        \__('Enable storing GraphQL execution logs, under file <code>%s</code>', 'gatographql'),
                        $relativeLogFile
                    ),
                    Properties::TYPE => Properties::TYPE_BOOL,
                ];
            }

            if (PluginStaticModuleConfiguration::canManageInstallingPluginSetupData()) {
                $option = self::OPTION_INSTALL_PLUGIN_SETUP_DATA;
                $moduleSettings[] = [
                    Properties::INPUT => $option,
                    Properties::NAME => $this->getSettingOptionName(
                        $module,
                        $option
                    ),
                    Properties::TITLE => \__('Plugin setup: Install Persisted Queries for common admin tasks?', 'gatographql'),
                    Properties::DESCRIPTION => sprintf(
                        \__('When installing or updating the plugin, enable the creation of Persisted Queries that tackle common admin tasks for WordPress?<br/><span class="more-info">%s</span>', 'gatographql'),
                        sprintf(
                            $moreInfoLabelPlaceholder,
                            'https://gatographql.com/guides/config/managing-plugin-setup-data',
                            \__('Managing the plugin\'s setup data', 'gatographql')
                        )
                    ),
                    Properties::TYPE => Properties::TYPE_BOOL,
                ];
            }

            $option = self::OPTION_ADD_RELEASE_NOTES_ADMIN_NOTICE;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Display admin notice with release notes?', 'gatographql'),
                Properties::DESCRIPTION => sprintf(
                    \__('Immediately after upgrading the plugin, show an admin notice with a link to the latest release notes?<br/><span class="more-info">%s</span>', 'gatographql'),
                    sprintf(
                        $moreInfoLabelPlaceholder,
                        'https://gatographql.com/guides/config/displaying-the-plugins-new-features',
                        \__('Displaying the plugin\'s new features', 'gatographql')
                    )
                ),
                Properties::TYPE => Properties::TYPE_BOOL,
            ];

            $option = self::OPTION_PRINT_SETTINGS_WITH_TABS;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Organize these settings under tabs?', 'gatographql'),
                Properties::DESCRIPTION => sprintf(
                    \__('Have all options in this Settings page be organized under tabs, one tab per module.<br/>After ticking the checkbox, must click on "Save Changes" to be applied<br/><span class="more-info">%s</span>', 'gatographql'),
                    sprintf(
                        $moreInfoLabelPlaceholder,
                        'https://gatographql.com/guides/config/printing-the-settings-page-with-tabs-or-long-format',
                        \__('Printing the Settings page with tabs or long format', 'gatographql')
                    )
                ),
                Properties::TYPE => Properties::TYPE_BOOL,
            ];
        } elseif ($module === self::SERVER_IP_CONFIGURATION) {
            // If any extension depends on this, it shall enable it
            if ($moduleConfiguration->enableSettingClientIPAddressServerPropertyName()) {
                $option = self::OPTION_CLIENT_IP_ADDRESS_SERVER_PROPERTY_NAME;
                $moduleSettings[] = [
                    Properties::INPUT => $option,
                    Properties::NAME => $this->getSettingOptionName(
                        $module,
                        $option
                    ),
                    Properties::TITLE => \__('$_SERVER property name to retrieve the client IP', 'gatographql'),
                    Properties::DESCRIPTION => sprintf(
                        '%s<br/><br/>%s<ul>%s</ul>',
                        \__('The visitor\'s IP address is retrieved from under the <code>$_SERVER</code> global variable, by default under property <code>\'REMOTE_ADDR\'</code>; depending on the platform or hosting provider, a different property may need to be used.', 'gatographql'),
                        \__('For instance:', 'gatographql'),
                        '<li>' . implode(
                            '</li><li>',
                            [
                                \__('Cloudflare might use <code>\'HTTP_CF_CONNECTING_IP\'</code>', 'gatographql'),
                                \__('AWS might use <code>\'HTTP_X_FORWARDED_FOR\'</code>', 'gatographql'),
                                \__('others', 'gatographql'),
                            ]
                        ) . '</li>'
                    ),
                    Properties::TYPE => Properties::TYPE_STRING,
                ];
            }
        } elseif ($module === self::SCHEMA_EDITING_ACCESS) {
            $possibleValues = [];
            foreach ($this->getUserAuthorizationSchemeRegistry()->getUserAuthorizationSchemes() as $userAuthorizationScheme) {
                $possibleValues[$userAuthorizationScheme->getName()] = $userAuthorizationScheme->getDescription();
            }
            /**
             * Write Access Scheme
             * If `"admin"`, only the admin can compose a GraphQL query and endpoint
             * If `"post"`, the workflow from creating posts is employed (i.e. Author role can create
             * but not publish the query, Editor role can publish it, etc)
             */
            $option = self::OPTION_EDITING_ACCESS_SCHEME;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Which users can edit the schema?', 'gatographql-schema-editing-access'),
                Properties::DESCRIPTION => sprintf(
                    \__('Indicate which users can edit the schema (i.e. creating and updating Persisted Queries, Custom Endpoints, and others)<br/><span class="more-info">%s</span>', 'gatographql-schema-editing-access'),
                    sprintf(
                        $moreInfoLabelPlaceholder,
                        'https://gatographql.com/guides/config/managing-who-can-edit-the-schema',
                        \__('Managing who can edit the schema', 'gatographql')
                    )
                ),
                Properties::TYPE => Properties::TYPE_STRING,
                Properties::POSSIBLE_VALUES => $possibleValues,
            ];
        }
        return $moduleSettings;
    }
}
