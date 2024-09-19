<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\Constants\HTMLCodes;
use GatoGraphQL\GatoGraphQL\Constants\ModuleSettingOptionValues;
use GatoGraphQL\GatoGraphQL\Constants\ModuleSettingOptions;
use GatoGraphQL\GatoGraphQL\ModuleSettings\Properties;
use GatoGraphQL\GatoGraphQL\Plugin;
use GatoGraphQL\GatoGraphQL\Services\Helpers\EndpointHelpers;
use GatoGraphQL\GatoGraphQL\Services\MenuPages\GraphQLVoyagerMenuPage;
use GatoGraphQL\GatoGraphQL\Services\MenuPages\GraphiQLMenuPage;
use GatoGraphQL\GatoGraphQL\Services\MenuPages\TutorialMenuPage;
use GraphQLByPoP\GraphQLEndpointForWP\Module as GraphQLEndpointForWPModule;
use GraphQLByPoP\GraphQLEndpointForWP\ModuleConfiguration as GraphQLEndpointForWPModuleConfiguration;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\Root\App;

class EndpointFunctionalityModuleResolver extends AbstractEndpointFunctionalityModuleResolver
{
    use ModuleResolverTrait;

    public final const PRIVATE_ENDPOINT = Plugin::NAMESPACE . '\private-endpoint';
    public final const SINGLE_ENDPOINT = Plugin::NAMESPACE . '\single-endpoint';

    private ?EndpointHelpers $endpointHelpers = null;
    private ?GraphiQLMenuPage $graphiQLMenuPage = null;
    private ?GraphQLVoyagerMenuPage $graphQLVoyagerMenuPage = null;
    private ?TutorialMenuPage $tutorialMenuPage = null;

    final public function setEndpointHelpers(EndpointHelpers $endpointHelpers): void
    {
        $this->endpointHelpers = $endpointHelpers;
    }
    final protected function getEndpointHelpers(): EndpointHelpers
    {
        if ($this->endpointHelpers === null) {
            /** @var EndpointHelpers */
            $endpointHelpers = $this->instanceManager->getInstance(EndpointHelpers::class);
            $this->endpointHelpers = $endpointHelpers;
        }
        return $this->endpointHelpers;
    }
    final public function setGraphiQLMenuPage(GraphiQLMenuPage $graphiQLMenuPage): void
    {
        $this->graphiQLMenuPage = $graphiQLMenuPage;
    }
    final protected function getGraphiQLMenuPage(): GraphiQLMenuPage
    {
        if ($this->graphiQLMenuPage === null) {
            /** @var GraphiQLMenuPage */
            $graphiQLMenuPage = $this->instanceManager->getInstance(GraphiQLMenuPage::class);
            $this->graphiQLMenuPage = $graphiQLMenuPage;
        }
        return $this->graphiQLMenuPage;
    }
    final public function setGraphQLVoyagerMenuPage(GraphQLVoyagerMenuPage $graphQLVoyagerMenuPage): void
    {
        $this->graphQLVoyagerMenuPage = $graphQLVoyagerMenuPage;
    }
    final protected function getGraphQLVoyagerMenuPage(): GraphQLVoyagerMenuPage
    {
        if ($this->graphQLVoyagerMenuPage === null) {
            /** @var GraphQLVoyagerMenuPage */
            $graphQLVoyagerMenuPage = $this->instanceManager->getInstance(GraphQLVoyagerMenuPage::class);
            $this->graphQLVoyagerMenuPage = $graphQLVoyagerMenuPage;
        }
        return $this->graphQLVoyagerMenuPage;
    }
    final public function setTutorialMenuPage(TutorialMenuPage $tutorialMenuPage): void
    {
        $this->tutorialMenuPage = $tutorialMenuPage;
    }
    final protected function getTutorialMenuPage(): TutorialMenuPage
    {
        if ($this->tutorialMenuPage === null) {
            /** @var TutorialMenuPage */
            $tutorialMenuPage = $this->instanceManager->getInstance(TutorialMenuPage::class);
            $this->tutorialMenuPage = $tutorialMenuPage;
        }
        return $this->tutorialMenuPage;
    }

    /**
     * @return string[]
     */
    public function getModulesToResolve(): array
    {
        return [
            self::PRIVATE_ENDPOINT,
            self::SINGLE_ENDPOINT,
        ];
    }

    /**
     * @return array<string[]> List of entries that must be satisfied, each entry is an array where at least 1 module must be satisfied
     */
    public function getDependedModuleLists(string $module): array
    {
        switch ($module) {
            case self::PRIVATE_ENDPOINT:
            case self::SINGLE_ENDPOINT:
                return [];
        }
        return parent::getDependedModuleLists($module);
    }

    public function getName(string $module): string
    {
        return match ($module) {
            self::PRIVATE_ENDPOINT => \__('Private Endpoint', 'gatographql'),
            self::SINGLE_ENDPOINT => \__('Single Endpoint', 'gatographql'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        /** @var GraphQLEndpointForWPModuleConfiguration */
        $moduleConfiguration = App::getModule(GraphQLEndpointForWPModule::class)->getConfiguration();
        return match ($module) {
            self::PRIVATE_ENDPOINT => \sprintf(
                \__('Private GraphQL endpoint, accessible only within the wp-admin, under <code>%s</code>', 'gatographql'),
                $this->getEndpointHelpers()->getAdminGraphQLEndpoint()
            ),
            self::SINGLE_ENDPOINT => \sprintf(
                \__('Expose the single GraphQL endpoint under <code>%s</code>', 'gatographql'),
                $moduleConfiguration->getGraphQLAPIEndpoint()
            ),
            default => parent::getDescription($module)
        };
    }

    public function isPredefinedEnabledOrDisabled(string $module): ?bool
    {
        return match ($module) {
            self::PRIVATE_ENDPOINT => true,
            default => parent::isPredefinedEnabledOrDisabled($module),
        };
    }

    public function isHidden(string $module): bool
    {
        return match ($module) {
            self::PRIVATE_ENDPOINT => true,
            default => parent::isHidden($module),
        };
    }

    /**
     * Default value for an option set by the module
     */
    public function getSettingsDefaultValue(string $module, string $option): mixed
    {
        $defaultValues = [
            self::PRIVATE_ENDPOINT => [
                ModuleSettingOptions::SCHEMA_CONFIGURATION => ModuleSettingOptionValues::NO_VALUE_ID,
            ],
            self::SINGLE_ENDPOINT => [
                ModuleSettingOptions::PATH => 'graphql',
                ModuleSettingOptions::SCHEMA_CONFIGURATION => ModuleSettingOptionValues::NO_VALUE_ID,
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
        // Do the if one by one, so that the SELECT do not get evaluated unless needed
        if ($module === self::SINGLE_ENDPOINT) {
            $option = ModuleSettingOptions::PATH;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Endpoint path', 'gatographql'),
                Properties::DESCRIPTION => \__('URL path to expose the single GraphQL endpoint', 'gatographql'),
                Properties::TYPE => Properties::TYPE_STRING,
            ];
        }

        // Add the Schema Configuration to all endpoints
        if (
            in_array($module, [
                self::PRIVATE_ENDPOINT,
                self::SINGLE_ENDPOINT,
            ]) && $this->getModuleRegistry()->isModuleEnabled(SchemaConfigurationFunctionalityModuleResolver::SCHEMA_CONFIGURATION)
        ) {
            $description = match ($module) {
                self::PRIVATE_ENDPOINT => sprintf(
                    \__('Schema Configuration to use in the private endpoint <code>%1$s</code>.<br/><br/>The private endpoint powers the admin\'s <a href="%2$s" target="_blank">GraphiQL%5$s</a> and <a href="%3$s" target="_blank">Interactive Schema%5$s</a> clients, and can be used to <a href="%4$s" target="_blank">feed data to blocks%5$s</a>.', 'gatographql'),
                    ltrim(
                        GeneralUtils::removeDomain($this->getEndpointHelpers()->getAdminGraphQLEndpoint()),
                        '/'
                    ),
                    \admin_url(sprintf(
                        'admin.php?page=%s',
                        $this->getGraphiQLMenuPage()->getScreenID()
                    )),
                    \admin_url(sprintf(
                        'admin.php?page=%s',
                        $this->getGraphQLVoyagerMenuPage()->getScreenID()
                    )),
                    'https://gatographql.com/guides/code/feeding-data-to-blocks-in-the-editor/',
                    HTMLCodes::OPEN_IN_NEW_WINDOW,
                ),
                self::SINGLE_ENDPOINT => \__('Schema Configuration to use in the Single Endpoint', 'gatographql'),
                default => '',
            };
            // Build all the possible values by fetching all the Schema Configuration posts
            $possibleValues = [
                ModuleSettingOptionValues::NO_VALUE_ID => \__('None', 'gatographql'),
            ];
            foreach ($this->getSchemaConfigurationCustomPosts() as $customPost) {
                $possibleValues[$customPost->ID] = $customPost->post_title;
            }
            $option = ModuleSettingOptions::SCHEMA_CONFIGURATION;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => in_array($module, [
                    self::PRIVATE_ENDPOINT,
                    self::SINGLE_ENDPOINT,
                ])
                    ? \__('Schema Configuration', 'gatographql')
                    : \__('Default Schema Configuration', 'gatographql'),
                Properties::DESCRIPTION => $description,
                Properties::TYPE => Properties::TYPE_INT,
                // Fetch all Schema Configurations from the DB
                Properties::POSSIBLE_VALUES => $possibleValues,
            ];
        }
        return $moduleSettings;
    }
}
