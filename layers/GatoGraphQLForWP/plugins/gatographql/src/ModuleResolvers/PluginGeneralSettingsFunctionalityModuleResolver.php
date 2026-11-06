<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ModuleResolvers;

use PoPSchema\Logger\Enums\LoggerSeverity;
use GatoGraphQL\GatoGraphQL\ContentProcessors\MarkdownContentParserInterface;
use GatoGraphQL\GatoGraphQL\Module;
use GatoGraphQL\GatoGraphQL\ModuleConfiguration;
use GatoGraphQL\GatoGraphQL\ModuleSettings\Properties;
use GatoGraphQL\GatoGraphQL\Plugin;
use GatoGraphQL\GatoGraphQL\PluginStaticModuleConfiguration;
use GatoGraphQL\GatoGraphQL\Registries\UserAuthorizationSchemeRegistryInterface;
use PoP\ComponentModel\App;
use PoP\Root\Environment as RootEnvironment;
use PoPSchema\Logger\Module as LoggerModule;
use PoPSchema\Logger\ModuleConfiguration as LoggerModuleConfiguration;

class PluginGeneralSettingsFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver
{
    use ModuleResolverTrait;
    use PluginGeneralSettingsFunctionalityModuleResolverTrait;

    public final const GENERAL = Plugin::NAMESPACE . '\general';
    public final const LOGS = Plugin::NAMESPACE . '\logs';
    public final const SERVER_IP_CONFIGURATION = Plugin::NAMESPACE . '\server-ip-configuration';
    public final const SCHEMA_EDITING_ACCESS = Plugin::NAMESPACE . '\schema-editing-access';

    /**
     * Setting options
     */
    public final const OPTION_ENABLE_SCHEMA_TUTORIAL = 'hide-tutorial-page';
    public final const OPTION_INSTALL_PLUGIN_SETUP_DATA = 'install-plugin-setup-data';
    public final const OPTION_ADD_RELEASE_NOTES_ADMIN_NOTICE = 'add-release-notes-admin-notice';
    public final const OPTION_PRINT_SETTINGS_WITH_TABS = 'print-settings-with-tabs';
    public final const OPTION_ENABLE_LOGS = 'enable-logs';
    public final const OPTION_ENABLE_LOGS_BY_SEVERITY = 'enable-logs-by-severity';
    public final const OPTION_ENABLE_LOG_COUNT_BADGES = 'enable-log-count-badges';
    public final const OPTION_ENABLE_LOG_COUNT_BADGES_BY_SEVERITY = 'enable-log-count-badges-by-severity';
    public final const OPTION_ENABLE_REVERSE_LOG_ORDER = 'enable-reverse-log-order';
    public final const OPTION_CLIENT_IP_ADDRESS_SERVER_PROPERTY_NAME = 'client-ip-address-server-property-name';
    public final const OPTION_EDITING_ACCESS_SCHEME = 'editing-access-scheme';

    private ?MarkdownContentParserInterface $markdownContentParser = null;
    private ?UserAuthorizationSchemeRegistryInterface $userAuthorizationSchemeRegistry = null;

    final protected function getMarkdownContentParser(): MarkdownContentParserInterface
    {
        if ($this->markdownContentParser === null) {
            /** @var MarkdownContentParserInterface */
            $markdownContentParser = $this->instanceManager->getInstance(MarkdownContentParserInterface::class);
            $this->markdownContentParser = $markdownContentParser;
        }
        return $this->markdownContentParser;
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
            self::LOGS,
            self::SERVER_IP_CONFIGURATION,
            self::SCHEMA_EDITING_ACCESS,
        ];
    }

    public function isPredefinedEnabledOrDisabled(string $module): ?bool
    {
        return match ($module) {
            self::GENERAL,
            self::LOGS,
            self::SERVER_IP_CONFIGURATION
                => true,
            default => parent::isPredefinedEnabledOrDisabled($module),
        };
    }

    public function isHidden(string $module): bool
    {
        return match ($module) {
            self::GENERAL,
            self::LOGS,
            self::SERVER_IP_CONFIGURATION
                => true,
            default => parent::isHidden($module),
        };
    }

    public function getName(string $module): string
    {
        return match ($module) {
            self::GENERAL => \__('General', 'gatographql'),
            self::LOGS => \__('Logs', 'gatographql'),
            self::SERVER_IP_CONFIGURATION => \__('Server IP', 'gatographql'),
            self::SCHEMA_EDITING_ACCESS => \__('Schema Editing Access', 'gatographql'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        return match ($module) {
            self::GENERAL => \__('General options for the plugin', 'gatographql'),
            self::LOGS => \__('Store and browse plugin logs', 'gatographql'),
            self::SERVER_IP_CONFIGURATION => \__('Configure retrieving the Client IP depending on the platform/environment', 'gatographql'),
            self::SCHEMA_EDITING_ACCESS => \__('Grant access to users other than admins to edit the GraphQL schema', 'gatographql'),
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

        $isApplicationEnvironmentDev = RootEnvironment::isApplicationEnvironmentDev();

        $defaultValues = [
            self::GENERAL => [
                self::OPTION_ENABLE_SCHEMA_TUTORIAL => false,
                self::OPTION_INSTALL_PLUGIN_SETUP_DATA => true,
                self::OPTION_ADD_RELEASE_NOTES_ADMIN_NOTICE => true,
                self::OPTION_PRINT_SETTINGS_WITH_TABS => true,
            ],
            self::LOGS => [
                self::OPTION_ENABLE_LOGS => true,
                self::OPTION_ENABLE_LOGS_BY_SEVERITY => [
                    LoggerSeverity::ERROR->value => true,
                    LoggerSeverity::WARNING->value => true,
                    LoggerSeverity::INFO->value => $isApplicationEnvironmentDev,
                    LoggerSeverity::DEBUG->value => $isApplicationEnvironmentDev,
                ],
                self::OPTION_ENABLE_LOG_COUNT_BADGES => true,
                self::OPTION_ENABLE_LOG_COUNT_BADGES_BY_SEVERITY => [
                    LoggerSeverity::ERROR->value => true,
                    LoggerSeverity::WARNING->value => true,
                    LoggerSeverity::INFO->value => false,
                    LoggerSeverity::DEBUG->value => false,
                ],
                self::OPTION_ENABLE_REVERSE_LOG_ORDER => false,
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
        if ($module === self::GENERAL) {
            $generalTabDisplayableOptionNames = $this->getGeneralTabDisplayableOptionNames();

            $option = self::OPTION_ENABLE_SCHEMA_TUTORIAL;
            if ($generalTabDisplayableOptionNames === null || in_array($option, $generalTabDisplayableOptionNames)) {
                $moduleSettings[] = [
                    Properties::INPUT => $option,
                    Properties::NAME => $this->getSettingOptionName(
                        $module,
                        $option
                    ),
                    Properties::TITLE => \__('Enable the Schema tutorial', 'gatographql'),
                    Properties::DESCRIPTION => \__('Add a menu page that explains the GraphQL schema, element by element.', 'gatographql'),
                    Properties::DOCS_URL => 'https://gatographql.com/guides/config/enabling-the-schema-tutorial-page',
                    Properties::TYPE => Properties::TYPE_BOOL,
                ];
            }

            $option = self::OPTION_INSTALL_PLUGIN_SETUP_DATA;
            if (
                PluginStaticModuleConfiguration::canManageInstallingPluginSetupData()
                && ($generalTabDisplayableOptionNames === null || in_array($option, $generalTabDisplayableOptionNames))
            ) {
                $moduleSettings[] = [
                    Properties::INPUT => $option,
                    Properties::NAME => $this->getSettingOptionName(
                        $module,
                        $option
                    ),
                    Properties::TITLE => \__('Install Persisted Queries for admin tasks', 'gatographql'),
                    Properties::DESCRIPTION => \__('Create Persisted Queries for common admin tasks when installing or updating the plugin.', 'gatographql'),
                    Properties::DOCS_URL => 'https://gatographql.com/guides/config/managing-plugin-setup-data',
                    Properties::TYPE => Properties::TYPE_BOOL,
                ];
            }

            $option = self::OPTION_ADD_RELEASE_NOTES_ADMIN_NOTICE;
            if ($generalTabDisplayableOptionNames === null || in_array($option, $generalTabDisplayableOptionNames)) {
                $moduleSettings[] = [
                    Properties::INPUT => $option,
                    Properties::NAME => $this->getSettingOptionName(
                        $module,
                        $option
                    ),
                    Properties::TITLE => \__('Show the release notes after upgrading', 'gatographql'),
                    Properties::DESCRIPTION => \__('After an upgrade, an admin notice links to the latest release notes.', 'gatographql'),
                    Properties::DOCS_URL => 'https://gatographql.com/guides/config/displaying-the-plugins-new-features',
                    Properties::TYPE => Properties::TYPE_BOOL,
                ];
            }

            $option = self::OPTION_PRINT_SETTINGS_WITH_TABS;
            if ($generalTabDisplayableOptionNames === null || in_array($option, $generalTabDisplayableOptionNames)) {
                $moduleSettings[] = [
                    Properties::INPUT => $option,
                    Properties::NAME => $this->getSettingOptionName(
                        $module,
                        $option
                    ),
                    Properties::TITLE => \__('Organize these settings under tabs', 'gatographql'),
                    Properties::DESCRIPTION => \__('Show each module of this page under its own tab.', 'gatographql'),
                    Properties::HELP => \__('The page shows the new layout once you save the settings.', 'gatographql'),
                    Properties::DOCS_URL => 'https://gatographql.com/guides/config/printing-the-settings-page-with-tabs-or-long-format',
                    Properties::TYPE => Properties::TYPE_BOOL,
                ];
            }
        } elseif ($module === self::LOGS && $moduleConfiguration->displayEnableLogsSettingsOption()) {
            /** @var LoggerModuleConfiguration */
            $loggerModuleConfiguration = App::getModule(LoggerModule::class)->getConfiguration();
            /** @var string */
            $logsDir = $loggerModuleConfiguration->getLogsDir();
            $relativeLogFolder = str_replace(
                constant('ABSPATH'),
                '',
                $logsDir
            );

            $moduleSettings[] = [
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    'group-logging'
                ),
                Properties::TITLE => \__('Logging', 'gatographql'),
                Properties::CONTROL => Properties::CONTROL_GROUP,
                Properties::TYPE => Properties::TYPE_NULL,
            ];

            $option = self::OPTION_ENABLE_LOGS;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Enable logs', 'gatographql'),
                Properties::DESCRIPTION => sprintf(
                    \__('Store the GraphQL execution logs, to browse under the <strong>%s</strong> menu.', 'gatographql'),
                    \__('Logs', 'gatographql')
                ),
                Properties::HELP => sprintf(
                    \__('The plugin stores the log files under folder <code>%s</code>.<br/><br/>Log files can grow very large, so clear them every now and then.', 'gatographql'),
                    $relativeLogFolder,
                ),
                Properties::TYPE => Properties::TYPE_BOOL,
            ];

            $option = self::OPTION_ENABLE_LOGS_BY_SEVERITY;
            $severityKeyLabels = $this->getSeverityKeyLabels();
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Enable logs by severity', 'gatographql'),
                Properties::DESCRIPTION => $this->getEnableLogsBySeverityDescription(),
                Properties::TYPE => Properties::TYPE_PROPERTY_ARRAY,
                Properties::KEY_LABELS => $severityKeyLabels,
                Properties::SUBTYPE => Properties::TYPE_BOOL,
                Properties::DEPENDS_ON => [
                    self::OPTION_ENABLE_LOGS => true,
                ],
            ];

            $option = self::OPTION_ENABLE_REVERSE_LOG_ORDER;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Newest entries first', 'gatographql'),
                Properties::DESCRIPTION => \__('A log file shows its newest entries at the top.', 'gatographql'),
                Properties::TYPE => Properties::TYPE_BOOL,
                Properties::DEPENDS_ON => [
                    self::OPTION_ENABLE_LOGS => true,
                ],
            ];

            $moduleSettings[] = [
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    'group-notifications'
                ),
                Properties::TITLE => \__('Notifications', 'gatographql'),
                Properties::CONTROL => Properties::CONTROL_GROUP,
                Properties::TYPE => Properties::TYPE_NULL,
            ];

            $option = self::OPTION_ENABLE_LOG_COUNT_BADGES;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Enable log notifications', 'gatographql'),
                Properties::DESCRIPTION => sprintf(
                    \__('The <strong>%s</strong> menu shows a badge with the number of new entries.', 'gatographql'),
                    \__('Logs', 'gatographql')
                ),
                Properties::TYPE => Properties::TYPE_BOOL,
            ];

            $option = self::OPTION_ENABLE_LOG_COUNT_BADGES_BY_SEVERITY;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Enable log notifications by severity', 'gatographql'),
                Properties::DESCRIPTION => \__('The severities that the badge counts.', 'gatographql'),
                Properties::TYPE => Properties::TYPE_PROPERTY_ARRAY,
                Properties::KEY_LABELS => $severityKeyLabels,
                Properties::SUBTYPE => Properties::TYPE_BOOL,
                Properties::DEPENDS_ON => [
                    self::OPTION_ENABLE_LOG_COUNT_BADGES => true,
                ],
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
                    Properties::TITLE => \__('Client IP property', 'gatographql'),
                    Properties::DESCRIPTION => \__('The property in <code>$_SERVER</code> that holds the visitor\'s IP address.', 'gatographql'),
                    Properties::HELP => sprintf(
                        '%s<br/><br/>%s<ul>%s</ul>',
                        \__('The default property is <code>\'REMOTE_ADDR\'</code>. Some platforms and hosting providers use a different one.', 'gatographql'),
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
                Properties::TITLE => \__('Who can edit the schema', 'gatographql'),
                Properties::DESCRIPTION => \__('The users who can create and update Persisted Queries, Custom Endpoints and others.', 'gatographql'),
                Properties::DOCS_URL => 'https://gatographql.com/guides/config/managing-who-can-edit-the-schema',
                Properties::TYPE => Properties::TYPE_STRING,
                Properties::POSSIBLE_VALUES => $possibleValues,
            ];
        }
        return $moduleSettings;
    }

    /**
     * Allow to select what options to display in the General tab
     *
     * @return string[]
     */
    public function getGeneralTabDisplayableOptionNames(): ?array
    {
        return null;
    }

    /**
     * Get the descriptions for each log severity level
     *
     * @return array<string,string> Array of severity => description
     */
    protected function getLogSeverityDescriptions(): array
    {
        return [
            LoggerSeverity::ERROR->value => \__('Critical issues that prevent the operation from completing', 'gatographql'),
            LoggerSeverity::WARNING->value => \__('Non-critical issues that may affect the operation', 'gatographql'),
            LoggerSeverity::INFO->value => \__('General information about the operation', 'gatographql'),
            LoggerSeverity::DEBUG->value => \__('Detailed information for debugging purposes', 'gatographql'),
        ];
    }

    /**
     * Get the description for enabling logs by severity
     */
    protected function getEnableLogsBySeverityDescription(): string
    {
        $severityDescriptions = $this->getLogSeverityDescriptions();
        $severityKeyLabels = $this->getSeverityKeyLabels();
        $severityItems = [];
        foreach ($severityDescriptions as $severity => $description) {
            $severityItems[] = sprintf(
                '<li><strong>%s</strong>: %s</li>',
                $severityKeyLabels[$severity],
                $description
            );
        }

        return sprintf(
            '%s%s',
            \__('The severities that the plugin logs.', 'gatographql'),
            $this->getCollapsible(
                sprintf(
                    '<ul>%s</ul>',
                    implode('', $severityItems)
                )
            )
        );
    }

    /**
     * Get the key labels for each severity level
     *
     * @return array<string,string> Array of severity => label
     */
    protected function getSeverityKeyLabels(): array
    {
        $placeholder = \__('%s %s', 'gatographql');
        return [
            LoggerSeverity::ERROR->value => sprintf($placeholder, LoggerSeverity::ERROR->sign(), \__('Error', 'gatographql')),
            LoggerSeverity::WARNING->value => sprintf($placeholder, LoggerSeverity::WARNING->sign(), \__('Warning', 'gatographql')),
            LoggerSeverity::INFO->value => sprintf($placeholder, LoggerSeverity::INFO->sign(), \__('Info', 'gatographql')),
            LoggerSeverity::DEBUG->value => sprintf($placeholder, LoggerSeverity::DEBUG->sign(), \__('Debug', 'gatographql')),
        ];
    }
}
