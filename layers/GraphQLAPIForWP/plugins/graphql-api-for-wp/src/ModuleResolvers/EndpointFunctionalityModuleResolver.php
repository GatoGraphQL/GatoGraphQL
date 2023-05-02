<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\Constants\HTMLCodes;
use GraphQLAPI\GraphQLAPI\Constants\ModuleSettingOptionValues;
use GraphQLAPI\GraphQLAPI\Constants\ModuleSettingOptions;
use GraphQLAPI\GraphQLAPI\ContentProcessors\MarkdownContentParserInterface;
use GraphQLAPI\GraphQLAPI\ModuleSettings\Properties;
use GraphQLAPI\GraphQLAPI\Plugin;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLSchemaConfigurationCustomPostType;
use GraphQLAPI\GraphQLAPI\SettingsCategoryResolvers\SettingsCategoryResolver;
use GraphQLByPoP\GraphQLEndpointForWP\Module as GraphQLEndpointForWPModule;
use GraphQLByPoP\GraphQLEndpointForWP\ModuleConfiguration as GraphQLEndpointForWPModuleConfiguration;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\Root\App;
use PoP\Root\Environment as RootEnvironment;
use WP_Post;

class EndpointFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver
{
    use ModuleResolverTrait;
    use EndpointFunctionalityModuleResolverTrait;

    public final const PRIVATE_ENDPOINT = Plugin::NAMESPACE . '\private-endpoint';
    public final const SINGLE_ENDPOINT = Plugin::NAMESPACE . '\single-endpoint';
    public final const CUSTOM_ENDPOINTS = Plugin::NAMESPACE . '\custom-endpoints';
    public final const PERSISTED_QUERIES = Plugin::NAMESPACE . '\persisted-queries';

    /** @var WP_Post[]|null */
    protected ?array $schemaConfigurationCustomPosts = null;

    private ?MarkdownContentParserInterface $markdownContentParser = null;
    private ?GraphQLSchemaConfigurationCustomPostType $graphQLSchemaConfigurationCustomPostType = null;

    final public function setMarkdownContentParser(MarkdownContentParserInterface $markdownContentParser): void
    {
        $this->markdownContentParser = $markdownContentParser;
    }
    final protected function getMarkdownContentParser(): MarkdownContentParserInterface
    {
        /** @var MarkdownContentParserInterface */
        return $this->markdownContentParser ??= $this->instanceManager->getInstance(MarkdownContentParserInterface::class);
    }
    final public function setGraphQLSchemaConfigurationCustomPostType(GraphQLSchemaConfigurationCustomPostType $graphQLSchemaConfigurationCustomPostType): void
    {
        $this->graphQLSchemaConfigurationCustomPostType = $graphQLSchemaConfigurationCustomPostType;
    }
    final protected function getGraphQLSchemaConfigurationCustomPostType(): GraphQLSchemaConfigurationCustomPostType
    {
        /** @var GraphQLSchemaConfigurationCustomPostType */
        return $this->graphQLSchemaConfigurationCustomPostType ??= $this->instanceManager->getInstance(GraphQLSchemaConfigurationCustomPostType::class);
    }

    /**
     * @return string[]
     */
    public function getModulesToResolve(): array
    {
        return [
            self::PRIVATE_ENDPOINT,
            self::SINGLE_ENDPOINT,
            self::CUSTOM_ENDPOINTS,
            self::PERSISTED_QUERIES,
        ];
    }

    public function getSettingsCategory(string $module): string
    {
        return SettingsCategoryResolver::ENDPOINT_CONFIGURATION;
    }

    /**
     * @return array<string[]> List of entries that must be satisfied, each entry is an array where at least 1 module must be satisfied
     */
    public function getDependedModuleLists(string $module): array
    {
        switch ($module) {
            case self::PRIVATE_ENDPOINT:
            case self::SINGLE_ENDPOINT:
            case self::CUSTOM_ENDPOINTS:
            case self::PERSISTED_QUERIES:
                return [];
        }
        return parent::getDependedModuleLists($module);
    }

    public function getName(string $module): string
    {
        return match ($module) {
            self::PRIVATE_ENDPOINT => \__('Private Endpoint', 'graphql-api'),
            self::SINGLE_ENDPOINT => \__('Single Endpoint', 'graphql-api'),
            self::CUSTOM_ENDPOINTS => \__('Custom Endpoints', 'graphql-api'),
            self::PERSISTED_QUERIES => \__('Persisted Queries', 'graphql-api'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        /** @var GraphQLEndpointForWPModuleConfiguration */
        $moduleConfiguration = App::getModule(GraphQLEndpointForWPModule::class)->getConfiguration();
        return match ($module) {
            self::PRIVATE_ENDPOINT => \sprintf(
                \__('Private GraphQL endpoint, accessible only within the wp-admin, under <code>%s</code>', 'graphql-api'),
                $this->getEndpointHelpers()->getAdminGraphQLEndpoint()
            ),
            self::SINGLE_ENDPOINT => \sprintf(
                \__('Expose the single GraphQL endpoint under <code>%s</code>', 'graphql-api'),
                $moduleConfiguration->getGraphQLAPIEndpoint()
            ),
            self::CUSTOM_ENDPOINTS => \__('Expose different subsets of the schema for different targets, such as users (clients, employees, etc), applications (website, mobile app, etc), context (weekday, weekend, etc), and others', 'graphql-api'),
            self::PERSISTED_QUERIES => \__('Expose predefined responses through a custom URL, akin to using GraphQL queries to publish REST endpoints', 'graphql-api'),
            default => parent::getDescription($module)
        };
    }

    public function isEnabledByDefault(string $module): bool
    {
        switch ($module) {
            case self::SINGLE_ENDPOINT:
                /**
                 * Single endpoint is naturally disabled for PROD.
                 * Enable for DEV to execute tests.
                 */
                return RootEnvironment::isApplicationEnvironmentDev();
        }
        return parent::isEnabledByDefault($module);
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
                ModuleSettingOptions::PATH => '/graphql/',
                ModuleSettingOptions::SCHEMA_CONFIGURATION => ModuleSettingOptionValues::NO_VALUE_ID,
            ],
            self::CUSTOM_ENDPOINTS => [
                ModuleSettingOptions::PATH => 'graphql',
                ModuleSettingOptions::SCHEMA_CONFIGURATION => ModuleSettingOptionValues::NO_VALUE_ID,
            ],
            self::PERSISTED_QUERIES => [
                ModuleSettingOptions::PATH => 'graphql-query',
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
                Properties::TITLE => \__('Endpoint path', 'graphql-api'),
                Properties::DESCRIPTION => \__('URL path to expose the single GraphQL endpoint', 'graphql-api'),
                Properties::TYPE => Properties::TYPE_STRING,
            ];
        } elseif ($module === self::CUSTOM_ENDPOINTS) {
            $option = ModuleSettingOptions::PATH;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Endpoint base slug', 'graphql-api'),
                Properties::DESCRIPTION => \__('URL base slug to expose the Custom Endpoint', 'graphql-api'),
                Properties::TYPE => Properties::TYPE_STRING,
            ];
        } elseif ($module === self::PERSISTED_QUERIES) {
            $option = ModuleSettingOptions::PATH;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Endpoint base slug', 'graphql-api'),
                Properties::DESCRIPTION => \__('URL base slug to expose the Persisted Query', 'graphql-api'),
                Properties::TYPE => Properties::TYPE_STRING,
            ];
        }

        // Add the Schema Configuration to all endpoints
        if (
            in_array($module, [
                self::PRIVATE_ENDPOINT,
                self::SINGLE_ENDPOINT,
                self::CUSTOM_ENDPOINTS,
                self::PERSISTED_QUERIES,
            ]) && $this->getModuleRegistry()->isModuleEnabled(SchemaConfigurationFunctionalityModuleResolver::SCHEMA_CONFIGURATION)
        ) {
            $defaultDescriptionPlaceholder = \__('Schema Configuration to use in %s which have option <code>"Default"</code> selected', 'graphql-api');
            $description = match ($module) {
                self::PRIVATE_ENDPOINT => sprintf(
                    \__('Schema Configuration to use in the Private Endpoint and Internal GraphQL Server:<ul><li>The private endpoint <code>%1$s</code> powers the admin\'s <a href="%2$s" target="_blank">GraphiQL%5$s</a> and <a href="%3$s" target="_blank">Interactive Schema%5$s</a> clients, and can be used to feed data to blocks in the WordPress editor</li><li>PHP class <code>%4$s</code> can be used to execute GraphQL queries internally (eg: triggered by some hook)</li></ul>', 'graphql-api'),
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
                    'InternalGraphQLServer',
                    HTMLCodes::OPEN_IN_NEW_WINDOW,
                ),
                self::SINGLE_ENDPOINT => \__('Schema Configuration to use in the Single Endpoint', 'graphql-api'),
                self::CUSTOM_ENDPOINTS => sprintf(
                    $defaultDescriptionPlaceholder,
                    \__('Custom Endpoints', 'graphql-api')
                ),
                self::PERSISTED_QUERIES => sprintf(
                    $defaultDescriptionPlaceholder,
                    \__('Persisted Queries', 'graphql-api')
                ),
                default => '',
            };
            // Build all the possible values by fetching all the Schema Configuration posts
            $possibleValues = [
                ModuleSettingOptionValues::NO_VALUE_ID => \__('None', 'graphql-api'),
            ];
            foreach ($this->getSchemaConfigurationCustomPosts() as $schemaConfigurationCustomPost) {
                $possibleValues[$schemaConfigurationCustomPost->ID] = $schemaConfigurationCustomPost->post_title;
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
                    ? \__('Schema Configuration', 'graphql-api')
                    : \__('Default Schema Configuration', 'graphql-api'),
                Properties::DESCRIPTION => $description,
                Properties::TYPE => Properties::TYPE_INT,
                // Fetch all Schema Configurations from the DB
                Properties::POSSIBLE_VALUES => $possibleValues,
            ];
        }
        return $moduleSettings;
    }

    /**
     * @return WP_Post[]
     */
    protected function getSchemaConfigurationCustomPosts(): array
    {
        if ($this->schemaConfigurationCustomPosts === null) {
            $this->schemaConfigurationCustomPosts = $this->doGetSchemaConfigurationCustomPosts();
        }

        return $this->schemaConfigurationCustomPosts;
    }

    /**
     * @return WP_Post[]
     */
    protected function doGetSchemaConfigurationCustomPosts(): array
    {
        /** @var GraphQLSchemaConfigurationCustomPostType */
        $graphQLSchemaConfigurationCustomPostType = $this->getGraphQLSchemaConfigurationCustomPostType();

        return \get_posts([
            'posts_per_page' => -1,
            'post_type' => $graphQLSchemaConfigurationCustomPostType->getCustomPostType(),
            'post_status' => 'publish',
        ]);
    }
}
