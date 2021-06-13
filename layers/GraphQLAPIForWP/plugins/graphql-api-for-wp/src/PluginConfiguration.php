<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI;

use GraphQLAPI\GraphQLAPI\ComponentConfiguration;
use GraphQLAPI\GraphQLAPI\Config\PluginConfigurationHelpers;
use GraphQLAPI\GraphQLAPI\Environment;
use GraphQLAPI\GraphQLAPI\Facades\CacheConfigurationManagerFacade;
use GraphQLAPI\GraphQLAPI\Facades\Registries\SystemModuleRegistryFacade;
use GraphQLAPI\GraphQLAPI\Facades\UserSettingsManagerFacade;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\CacheFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ClientFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\OperationalFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\PerformanceFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\PluginManagementFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaTypeModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\UserInterfaceFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\PluginManagement\MainPluginManager;
use GraphQLAPI\GraphQLAPI\Services\Helpers\EndpointHelpers;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\SettingsMenuPage;
use GraphQLByPoP\GraphQLClientsForWP\ComponentConfiguration as GraphQLClientsForWPComponentConfiguration;
use GraphQLByPoP\GraphQLClientsForWP\Environment as GraphQLClientsForWPEnvironment;
use GraphQLByPoP\GraphQLEndpointForWP\ComponentConfiguration as GraphQLEndpointForWPComponentConfiguration;
use GraphQLByPoP\GraphQLEndpointForWP\Environment as GraphQLEndpointForWPEnvironment;
use GraphQLByPoP\GraphQLQuery\Environment as GraphQLQueryEnvironment;
use GraphQLByPoP\GraphQLServer\ComponentConfiguration as GraphQLServerComponentConfiguration;
use GraphQLByPoP\GraphQLServer\Configuration\MutationSchemes;
use GraphQLByPoP\GraphQLServer\Environment as GraphQLServerEnvironment;
use PoP\AccessControl\ComponentConfiguration as AccessControlComponentConfiguration;
use PoP\AccessControl\Environment as AccessControlEnvironment;
use PoP\AccessControl\Schema\SchemaModes;
use PoP\APIEndpoints\EndpointUtils;
use PoP\CacheControl\ComponentConfiguration as CacheControlComponentConfiguration;
use PoP\CacheControl\Environment as CacheControlEnvironment;
use PoP\ComponentModel\ComponentConfiguration as ComponentModelComponentConfiguration;
use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationHelpers;
use PoP\ComponentModel\Environment as ComponentModelEnvironment;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\Facades\Instances\SystemInstanceManagerFacade;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\Engine\ComponentConfiguration as EngineComponentConfiguration;
use PoP\Engine\Environment as EngineEnvironment;
use PoP\Root\Environment as RootEnvironment;
use PoPSchema\Categories\ComponentConfiguration as CategoriesComponentConfiguration;
use PoPSchema\Categories\Environment as CategoriesEnvironment;
use PoPSchema\CommentMeta\ComponentConfiguration as CommentMetaComponentConfiguration;
use PoPSchema\CommentMeta\Environment as CommentMetaEnvironment;
use PoPSchema\CustomPostMeta\ComponentConfiguration as CustomPostMetaComponentConfiguration;
use PoPSchema\CustomPostMeta\Environment as CustomPostMetaEnvironment;
use PoPSchema\CustomPosts\ComponentConfiguration as CustomPostsComponentConfiguration;
use PoPSchema\CustomPosts\Environment as CustomPostsEnvironment;
use PoPSchema\GenericCustomPosts\ComponentConfiguration as GenericCustomPostsComponentConfiguration;
use PoPSchema\GenericCustomPosts\Environment as GenericCustomPostsEnvironment;
use PoPSchema\Pages\ComponentConfiguration as PagesComponentConfiguration;
use PoPSchema\Pages\Environment as PagesEnvironment;
use PoPSchema\Posts\ComponentConfiguration as PostsComponentConfiguration;
use PoPSchema\Posts\Environment as PostsEnvironment;
use PoPSchema\SchemaCommons\Constants\Behaviors;
use PoPSchema\Settings\ComponentConfiguration as SettingsComponentConfiguration;
use PoPSchema\Settings\Environment as SettingsEnvironment;
use PoPSchema\Tags\ComponentConfiguration as TagsComponentConfiguration;
use PoPSchema\Tags\Environment as TagsEnvironment;
use PoPSchema\TaxonomyMeta\ComponentConfiguration as TaxonomyMetaComponentConfiguration;
use PoPSchema\TaxonomyMeta\Environment as TaxonomyMetaEnvironment;
use PoPSchema\UserMeta\ComponentConfiguration as UserMetaComponentConfiguration;
use PoPSchema\UserMeta\Environment as UserMetaEnvironment;
use PoPSchema\Users\ComponentConfiguration as UsersComponentConfiguration;
use PoPSchema\Users\Environment as UsersEnvironment;

/**
 * Sets the configuration in all the PoP components.
 *
 * To set the value for properties, it uses this order:
 *
 * 1. Retrieve it as an environment value, if defined
 * 2. Retrieve as a constant `GRAPHQL_API_...` from wp-config.php, if defined
 * 3. Retrieve it from the user settings, if stored
 * 4. Use the default value
 *
 * If a slug is set or updated in the environment variable or wp-config constant,
 * it is necessary to flush the rewrite rules for the change to take effect.
 * For that, on the WordPress admin, go to Settings => Permalinks and click on Save changes
 */
class PluginConfiguration
{
    /**
     * Cache the options after normalizing them
     *
     * @var array<string, mixed>|null
     */
    protected static ?array $normalizedOptionValuesCache = null;

    /**
     * Cache the Container Cache Configuration
     *
     * @var array<mixed> Array with args to pass to `AppLoader::initializeContainers` - [0]: cache container? (bool), [1]: container namespace (string|null)
     */
    protected static ?array $containerCacheConfigurationCache = null;

    /**
     * Initialize all configuration
     */
    public static function initialize(): void
    {
        self::mapEnvVariablesToWPConfigConstants();
        self::defineEnvironmentConstantsFromSettings();
        self::defineEnvironmentConstantsFromCallbacks();
    }

    /**
     * Get the values from the form submitted to options.php, and normalize them
     *
     * @return array<string, mixed>
     */
    protected static function getNormalizedOptionValues(): array
    {
        if (is_null(self::$normalizedOptionValuesCache)) {
            $instanceManager = InstanceManagerFacade::getInstance();
            /**
             * @var SettingsMenuPage
             */
            $settingsMenuPage = $instanceManager->getInstance(SettingsMenuPage::class);
            // Obtain the values from the POST and normalize them
            $value = $_POST[SettingsMenuPage::SETTINGS_FIELD] ?? [];
            self::$normalizedOptionValuesCache = $settingsMenuPage->normalizeSettings($value);
        }
        return self::$normalizedOptionValuesCache;
    }

    /**
     * If we are in options.php, already set the new slugs in the hook,
     * so that the EndpointHandler's `addRewriteEndpoints` (executed on `init`)
     * adds the rewrite with the new slug, which will be persisted on
     * flushing the rewrite rules
     *
     * Hidden input "form-origin" is used to only execute for this plugin,
     * since options.php is used everywhere, including WP core and other plugins.
     * Otherwise, it may thrown an exception!
     */
    protected static function maybeOverrideValueFromForm(mixed $value, string $module, string $option): mixed
    {
        global $pagenow;
        if (
            $pagenow == 'options.php'
            && isset($_REQUEST[SettingsMenuPage::FORM_ORIGIN])
            && $_REQUEST[SettingsMenuPage::FORM_ORIGIN] == SettingsMenuPage::SETTINGS_FIELD
        ) {
            $value = self::getNormalizedOptionValues();
            // Return the specific value to this module/option
            $moduleRegistry = SystemModuleRegistryFacade::getInstance();
            $moduleResolver = $moduleRegistry->getModuleResolver($module);
            $optionName = $moduleResolver->getSettingOptionName($module, $option);
            return $value[$optionName];
        }
        return $value;
    }

    /**
     * Process the "URL path" option values
     */
    protected static function getURLPathSettingValue(
        string $value,
        string $module,
        string $option
    ): string {
        // If we are on options.php, use the value submitted to the form,
        // so it's updated before doing `add_rewrite_endpoint` and `flush_rewrite_rules`
        $value = self::maybeOverrideValueFromForm($value, $module, $option);

        // Make sure the path has a "/" on both ends
        return EndpointUtils::slashURI($value);
    }

    /**
     * Process the "URL base path" option values
     */
    protected static function getCPTPermalinkBasePathSettingValue(
        string $value,
        string $module,
        string $option
    ): string {
        // If we are on options.php, use the value submitted to the form,
        // so it's updated before doing `add_rewrite_endpoint` and `flush_rewrite_rules`
        $value = self::maybeOverrideValueFromForm($value, $module, $option);

        // Make sure the path does not have "/" on either end
        return trim($value, '/');
    }

    /**
     * Define the values for certain environment constants from the plugin settings
     */
    protected static function defineEnvironmentConstantsFromSettings(): void
    {
        $moduleRegistry = SystemModuleRegistryFacade::getInstance();
        // All the environment variables to override
        $mappings = [
            // Editing Access Scheme
            [
                'class' => ComponentConfiguration::class,
                'envVariable' => Environment::EDITING_ACCESS_SCHEME,
                'module' => PluginManagementFunctionalityModuleResolver::SCHEMA_EDITING_ACCESS,
                'option' => PluginManagementFunctionalityModuleResolver::OPTION_EDITING_ACCESS_SCHEME,
            ],
            // GraphQL single endpoint slug
            [
                'class' => GraphQLEndpointForWPComponentConfiguration::class,
                'envVariable' => GraphQLEndpointForWPEnvironment::GRAPHQL_API_ENDPOINT,
                'module' => EndpointFunctionalityModuleResolver::SINGLE_ENDPOINT,
                'option' => EndpointFunctionalityModuleResolver::OPTION_PATH,
                'callback' => fn ($value) => self::getURLPathSettingValue(
                    $value,
                    EndpointFunctionalityModuleResolver::SINGLE_ENDPOINT,
                    EndpointFunctionalityModuleResolver::OPTION_PATH
                ),
                'condition' => 'any',
            ],
            // Custom Endpoint path
            [
                'class' => ComponentConfiguration::class,
                'envVariable' => Environment::ENDPOINT_SLUG_BASE,
                'module' => EndpointFunctionalityModuleResolver::CUSTOM_ENDPOINTS,
                'option' => EndpointFunctionalityModuleResolver::OPTION_PATH,
                'callback' => fn ($value) => self::getCPTPermalinkBasePathSettingValue(
                    $value,
                    EndpointFunctionalityModuleResolver::CUSTOM_ENDPOINTS,
                    EndpointFunctionalityModuleResolver::OPTION_PATH
                ),
                'condition' => 'any',
            ],
            // Persisted Query path
            [
                'class' => ComponentConfiguration::class,
                'envVariable' => Environment::PERSISTED_QUERY_SLUG_BASE,
                'module' => EndpointFunctionalityModuleResolver::PERSISTED_QUERIES,
                'option' => EndpointFunctionalityModuleResolver::OPTION_PATH,
                'callback' => fn ($value) => self::getCPTPermalinkBasePathSettingValue(
                    $value,
                    EndpointFunctionalityModuleResolver::PERSISTED_QUERIES,
                    EndpointFunctionalityModuleResolver::OPTION_PATH
                ),
                'condition' => 'any',
            ],
            // GraphiQL client slug
            [
                'class' => GraphQLClientsForWPComponentConfiguration::class,
                'envVariable' => GraphQLClientsForWPEnvironment::GRAPHIQL_CLIENT_ENDPOINT,
                'module' => ClientFunctionalityModuleResolver::GRAPHIQL_FOR_SINGLE_ENDPOINT,
                'option' => EndpointFunctionalityModuleResolver::OPTION_PATH,
                'callback' => fn ($value) => self::getURLPathSettingValue(
                    $value,
                    ClientFunctionalityModuleResolver::GRAPHIQL_FOR_SINGLE_ENDPOINT,
                    EndpointFunctionalityModuleResolver::OPTION_PATH
                ),
                'condition' => 'any',
            ],
            // Voyager client slug
            [
                'class' => GraphQLClientsForWPComponentConfiguration::class,
                'envVariable' => GraphQLClientsForWPEnvironment::VOYAGER_CLIENT_ENDPOINT,
                'module' => ClientFunctionalityModuleResolver::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT,
                'option' => EndpointFunctionalityModuleResolver::OPTION_PATH,
                'callback' => fn ($value) => self::getURLPathSettingValue(
                    $value,
                    ClientFunctionalityModuleResolver::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT,
                    EndpointFunctionalityModuleResolver::OPTION_PATH
                ),
                'condition' => 'any',
            ],
            // Use private schema mode?
            [
                'class' => AccessControlComponentConfiguration::class,
                'envVariable' => AccessControlEnvironment::USE_PRIVATE_SCHEMA_MODE,
                'module' => SchemaConfigurationFunctionalityModuleResolver::PUBLIC_PRIVATE_SCHEMA,
                'option' => SchemaConfigurationFunctionalityModuleResolver::OPTION_MODE,
                // It is stored as string "private" in DB, and must be passed as bool `true` to component
                'callback' => fn ($value) => $value == SchemaModes::PRIVATE_SCHEMA_MODE,
            ],
            // Enable individual access control for the schema mode?
            [
                'class' => AccessControlComponentConfiguration::class,
                'envVariable' => AccessControlEnvironment::ENABLE_INDIVIDUAL_CONTROL_FOR_PUBLIC_PRIVATE_SCHEMA_MODE,
                'module' => SchemaConfigurationFunctionalityModuleResolver::PUBLIC_PRIVATE_SCHEMA,
                'option' => SchemaConfigurationFunctionalityModuleResolver::OPTION_ENABLE_GRANULAR,
                // Also make sure that the module is enabled.
                // Otherwise set the value in `false`, to override a potential `true` in the Settings
                'callback' => fn ($value) => $moduleRegistry->isModuleEnabled(SchemaConfigurationFunctionalityModuleResolver::PUBLIC_PRIVATE_SCHEMA) && $value,
                'condition' => 'any',
            ],
            // Use namespacing?
            [
                'class' => ComponentModelComponentConfiguration::class,
                'envVariable' => ComponentModelEnvironment::NAMESPACE_TYPES_AND_INTERFACES,
                'module' => SchemaConfigurationFunctionalityModuleResolver::SCHEMA_NAMESPACING,
                'option' => SchemaConfigurationFunctionalityModuleResolver::OPTION_USE_NAMESPACING,
            ],
            // Enable nested mutations?
            [
                'class' => GraphQLServerComponentConfiguration::class,
                'envVariable' => GraphQLServerEnvironment::ENABLE_NESTED_MUTATIONS,
                'module' => OperationalFunctionalityModuleResolver::NESTED_MUTATIONS,
                'option' => OperationalFunctionalityModuleResolver::OPTION_SCHEME,
                'callback' => fn ($value) => $moduleRegistry->isModuleEnabled(OperationalFunctionalityModuleResolver::NESTED_MUTATIONS) && $value != MutationSchemes::STANDARD,
            ],
            // Disable redundant mutation fields in the root type?
            [
                'class' => EngineComponentConfiguration::class,
                'envVariable' => EngineEnvironment::DISABLE_REDUNDANT_ROOT_TYPE_MUTATION_FIELDS,
                'module' => OperationalFunctionalityModuleResolver::NESTED_MUTATIONS,
                'option' => OperationalFunctionalityModuleResolver::OPTION_SCHEME,
                'callback' => fn ($value) => $moduleRegistry->isModuleEnabled(OperationalFunctionalityModuleResolver::NESTED_MUTATIONS) && $value == MutationSchemes::NESTED_WITHOUT_REDUNDANT_ROOT_FIELDS,
            ],
            // Cache-Control default max-age
            [
                'class' => CacheControlComponentConfiguration::class,
                'envVariable' => CacheControlEnvironment::DEFAULT_CACHE_CONTROL_MAX_AGE,
                'module' => PerformanceFunctionalityModuleResolver::CACHE_CONTROL,
                'option' => PerformanceFunctionalityModuleResolver::OPTION_MAX_AGE,
            ],
            // Custom Post default/max limits, Supported custom post types
            [
                'class' => GenericCustomPostsComponentConfiguration::class,
                'envVariable' => GenericCustomPostsEnvironment::GENERIC_CUSTOMPOST_LIST_DEFAULT_LIMIT,
                'module' => SchemaTypeModuleResolver::SCHEMA_GENERIC_CUSTOMPOSTS,
                'optionModule' => SchemaTypeModuleResolver::SCHEMA_CUSTOMPOSTS,
                'option' => SchemaTypeModuleResolver::OPTION_LIST_DEFAULT_LIMIT,
            ],
            // [
            //     'class' => GenericCustomPostsComponentConfiguration::class,
            //     'envVariable' => GenericCustomPostsEnvironment::GENERIC_CUSTOMPOST_LIST_MAX_LIMIT,
            //     'module' => SchemaTypeModuleResolver::SCHEMA_GENERIC_CUSTOMPOSTS,
            //     'optionModule' => SchemaTypeModuleResolver::SCHEMA_CUSTOMPOSTS,
            //     'option' => SchemaTypeModuleResolver::OPTION_LIST_MAX_LIMIT,
            // ],
            [
                'class' => GenericCustomPostsComponentConfiguration::class,
                'envVariable' => GenericCustomPostsEnvironment::GENERIC_CUSTOMPOST_TYPES,
                'module' => SchemaTypeModuleResolver::SCHEMA_GENERIC_CUSTOMPOSTS,
                'option' => SchemaTypeModuleResolver::OPTION_CUSTOMPOST_TYPES,
            ],
            // Post default/max limits, add to CustomPostUnion
            [
                'class' => PostsComponentConfiguration::class,
                'envVariable' => PostsEnvironment::POST_LIST_DEFAULT_LIMIT,
                'module' => SchemaTypeModuleResolver::SCHEMA_POSTS,
                'optionModule' => SchemaTypeModuleResolver::SCHEMA_CUSTOMPOSTS,
                'option' => SchemaTypeModuleResolver::OPTION_LIST_DEFAULT_LIMIT,
            ],
            [
                'class' => PostsComponentConfiguration::class,
                'envVariable' => PostsEnvironment::POST_LIST_MAX_LIMIT,
                'module' => SchemaTypeModuleResolver::SCHEMA_POSTS,
                'optionModule' => SchemaTypeModuleResolver::SCHEMA_CUSTOMPOSTS,
                'option' => SchemaTypeModuleResolver::OPTION_LIST_MAX_LIMIT,
            ],
            [
                'class' => PostsComponentConfiguration::class,
                'envVariable' => PostsEnvironment::ADD_POST_TYPE_TO_CUSTOMPOST_UNION_TYPES,
                'module' => SchemaTypeModuleResolver::SCHEMA_POSTS,
                'option' => SchemaTypeModuleResolver::OPTION_ADD_TYPE_TO_CUSTOMPOST_UNION_TYPE,
            ],
            // User default/max limits
            [
                'class' => UsersComponentConfiguration::class,
                'envVariable' => UsersEnvironment::USER_LIST_DEFAULT_LIMIT,
                'module' => SchemaTypeModuleResolver::SCHEMA_USERS,
                'option' => SchemaTypeModuleResolver::OPTION_LIST_DEFAULT_LIMIT,
            ],
            [
                'class' => UsersComponentConfiguration::class,
                'envVariable' => UsersEnvironment::USER_LIST_MAX_LIMIT,
                'module' => SchemaTypeModuleResolver::SCHEMA_USERS,
                'option' => SchemaTypeModuleResolver::OPTION_LIST_MAX_LIMIT,
            ],
            // Tag default/max limits
            [
                'class' => TagsComponentConfiguration::class,
                'envVariable' => TagsEnvironment::TAG_LIST_DEFAULT_LIMIT,
                'module' => SchemaTypeModuleResolver::SCHEMA_TAGS,
                'option' => SchemaTypeModuleResolver::OPTION_LIST_DEFAULT_LIMIT,
            ],
            [
                'class' => TagsComponentConfiguration::class,
                'envVariable' => TagsEnvironment::TAG_LIST_MAX_LIMIT,
                'module' => SchemaTypeModuleResolver::SCHEMA_TAGS,
                'option' => SchemaTypeModuleResolver::OPTION_LIST_MAX_LIMIT,
            ],
            // Categories default/max limits
            [
                'class' => CategoriesComponentConfiguration::class,
                'envVariable' => CategoriesEnvironment::CATEGORY_LIST_DEFAULT_LIMIT,
                'module' => SchemaTypeModuleResolver::SCHEMA_CATEGORIES,
                'option' => SchemaTypeModuleResolver::OPTION_LIST_DEFAULT_LIMIT,
            ],
            [
                'class' => CategoriesComponentConfiguration::class,
                'envVariable' => CategoriesEnvironment::CATEGORY_LIST_MAX_LIMIT,
                'module' => SchemaTypeModuleResolver::SCHEMA_CATEGORIES,
                'option' => SchemaTypeModuleResolver::OPTION_LIST_MAX_LIMIT,
            ],
            // Page default/max limits, add to CustomPostUnion
            [
                'class' => PagesComponentConfiguration::class,
                'envVariable' => PagesEnvironment::PAGE_LIST_DEFAULT_LIMIT,
                'module' => SchemaTypeModuleResolver::SCHEMA_PAGES,
                'optionModule' => SchemaTypeModuleResolver::SCHEMA_CUSTOMPOSTS,
                'option' => SchemaTypeModuleResolver::OPTION_LIST_DEFAULT_LIMIT,
            ],
            [
                'class' => PagesComponentConfiguration::class,
                'envVariable' => PagesEnvironment::PAGE_LIST_MAX_LIMIT,
                'module' => SchemaTypeModuleResolver::SCHEMA_PAGES,
                'optionModule' => SchemaTypeModuleResolver::SCHEMA_CUSTOMPOSTS,
                'option' => SchemaTypeModuleResolver::OPTION_LIST_MAX_LIMIT,
            ],
            [
                'class' => PagesComponentConfiguration::class,
                'envVariable' => PagesEnvironment::ADD_PAGE_TYPE_TO_CUSTOMPOST_UNION_TYPES,
                'module' => SchemaTypeModuleResolver::SCHEMA_PAGES,
                'option' => SchemaTypeModuleResolver::OPTION_ADD_TYPE_TO_CUSTOMPOST_UNION_TYPE,
            ],
            // Custom post default/max limits
            [
                'class' => CustomPostsComponentConfiguration::class,
                'envVariable' => CustomPostsEnvironment::CUSTOMPOST_LIST_DEFAULT_LIMIT,
                'module' => SchemaTypeModuleResolver::SCHEMA_CUSTOMPOSTS,
                'option' => SchemaTypeModuleResolver::OPTION_LIST_DEFAULT_LIMIT,
            ],
            [
                'class' => CustomPostsComponentConfiguration::class,
                'envVariable' => CustomPostsEnvironment::CUSTOMPOST_LIST_MAX_LIMIT,
                'module' => SchemaTypeModuleResolver::SCHEMA_CUSTOMPOSTS,
                'option' => SchemaTypeModuleResolver::OPTION_LIST_MAX_LIMIT,
            ],
            // Custom post, if there is only one custom type, use it instead of the Union
            [
                'class' => CustomPostsComponentConfiguration::class,
                'envVariable' => CustomPostsEnvironment::USE_SINGLE_TYPE_INSTEAD_OF_CUSTOMPOST_UNION_TYPE,
                'module' => SchemaTypeModuleResolver::SCHEMA_CUSTOMPOSTS,
                'option' => SchemaTypeModuleResolver::OPTION_USE_SINGLE_TYPE_INSTEAD_OF_UNION_TYPE,
            ],
            // White/Blacklisted entries to Root.option
            [
                'class' => SettingsComponentConfiguration::class,
                'envVariable' => SettingsEnvironment::SETTINGS_ENTRIES,
                'module' => SchemaTypeModuleResolver::SCHEMA_SETTINGS,
                'option' => SchemaTypeModuleResolver::OPTION_ENTRIES,
                // Remove whitespaces, and empty entries (they mess up with regex)
                'callback' => fn (array $value) => array_filter(array_map('trim', $value)),
            ],
            [
                'class' => SettingsComponentConfiguration::class,
                'envVariable' => SettingsEnvironment::SETTINGS_BEHAVIOR,
                'module' => SchemaTypeModuleResolver::SCHEMA_SETTINGS,
                'option' => SchemaTypeModuleResolver::OPTION_BEHAVIOR,
            ],
            // White/Blacklisted entries to CustomPost.meta
            [
                'class' => CustomPostMetaComponentConfiguration::class,
                'envVariable' => CustomPostMetaEnvironment::CUSTOMPOST_META_ENTRIES,
                'module' => SchemaTypeModuleResolver::SCHEMA_CUSTOMPOST_META,
                'option' => SchemaTypeModuleResolver::OPTION_ENTRIES,
                // Remove whitespaces, and empty entries (they mess up with regex)
                'callback' => fn (array $value) => array_filter(array_map('trim', $value)),
            ],
            [
                'class' => CustomPostMetaComponentConfiguration::class,
                'envVariable' => CustomPostMetaEnvironment::CUSTOMPOST_META_BEHAVIOR,
                'module' => SchemaTypeModuleResolver::SCHEMA_CUSTOMPOST_META,
                'option' => SchemaTypeModuleResolver::OPTION_BEHAVIOR,
            ],
            // White/Blacklisted entries to User.meta
            [
                'class' => UserMetaComponentConfiguration::class,
                'envVariable' => UserMetaEnvironment::USER_META_ENTRIES,
                'module' => SchemaTypeModuleResolver::SCHEMA_USER_META,
                'option' => SchemaTypeModuleResolver::OPTION_ENTRIES,
                // Remove whitespaces, and empty entries (they mess up with regex)
                'callback' => fn (array $value) => array_filter(array_map('trim', $value)),
            ],
            [
                'class' => UserMetaComponentConfiguration::class,
                'envVariable' => UserMetaEnvironment::USER_META_BEHAVIOR,
                'module' => SchemaTypeModuleResolver::SCHEMA_USER_META,
                'option' => SchemaTypeModuleResolver::OPTION_BEHAVIOR,
            ],
            // White/Blacklisted entries to Comment.meta
            [
                'class' => CommentMetaComponentConfiguration::class,
                'envVariable' => CommentMetaEnvironment::COMMENT_META_ENTRIES,
                'module' => SchemaTypeModuleResolver::SCHEMA_COMMENT_META,
                'option' => SchemaTypeModuleResolver::OPTION_ENTRIES,
                // Remove whitespaces, and empty entries (they mess up with regex)
                'callback' => fn (array $value) => array_filter(array_map('trim', $value)),
            ],
            [
                'class' => CommentMetaComponentConfiguration::class,
                'envVariable' => CommentMetaEnvironment::COMMENT_META_BEHAVIOR,
                'module' => SchemaTypeModuleResolver::SCHEMA_COMMENT_META,
                'option' => SchemaTypeModuleResolver::OPTION_BEHAVIOR,
            ],
            // White/Blacklisted entries to PostTag.meta and PostCategory.meta
            [
                'class' => TaxonomyMetaComponentConfiguration::class,
                'envVariable' => TaxonomyMetaEnvironment::TAXONOMY_META_ENTRIES,
                'module' => SchemaTypeModuleResolver::SCHEMA_TAXONOMY_META,
                'option' => SchemaTypeModuleResolver::OPTION_ENTRIES,
                // Remove whitespaces, and empty entries (they mess up with regex)
                'callback' => fn (array $value) => array_filter(array_map('trim', $value)),
            ],
            [
                'class' => TaxonomyMetaComponentConfiguration::class,
                'envVariable' => TaxonomyMetaEnvironment::TAXONOMY_META_BEHAVIOR,
                'module' => SchemaTypeModuleResolver::SCHEMA_TAXONOMY_META,
                'option' => SchemaTypeModuleResolver::OPTION_BEHAVIOR,
            ],
            // Enable the "admin" schema: if doing ?behavior=unrestricted, it will already
            // be set by configuration. Otherwise, it uses this mapping
            [
                'class' => ComponentModelComponentConfiguration::class,
                'envVariable' => ComponentModelEnvironment::ENABLE_ADMIN_SCHEMA,
                'module' => SchemaTypeModuleResolver::SCHEMA_ADMIN_SCHEMA,
                'option' => SchemaTypeModuleResolver::OPTION_ENABLE_ADMIN_SCHEMA,
            ],
        ];
        // For each environment variable, see if its value has been saved in the settings
        $userSettingsManager = UserSettingsManagerFacade::getInstance();
        foreach ($mappings as $mapping) {
            $module = $mapping['module'];
            $condition = $mapping['condition'] ?? true;
            // Check if the hook must be executed always (condition => 'any') or with
            // stated enabled (true) or disabled (false). By default, it's enabled
            if ($condition !== 'any' && $condition !== $moduleRegistry->isModuleEnabled($module)) {
                continue;
            }
            // If the environment value has been defined, or the constant in wp-config.php,
            // then do nothing, since they have priority
            $envVariable = $mapping['envVariable'];
            if (getenv($envVariable) !== false || PluginConfigurationHelpers::isWPConfigConstantDefined($envVariable)) {
                continue;
            }
            $hookName = ComponentConfigurationHelpers::getHookName(
                $mapping['class'],
                $envVariable
            );
            $option = $mapping['option'];
            $optionModule = $mapping['optionModule'] ?? $module;
            // Make explicit it can be null so that PHPStan level 3 doesn't fail
            $callback = $mapping['callback'] ?? null;
            \add_filter(
                $hookName,
                function () use ($userSettingsManager, $optionModule, $option, $callback) {
                    $value = $userSettingsManager->getSetting($optionModule, $option);
                    if (!is_null($callback)) {
                        return $callback($value);
                    }
                    return $value;
                }
            );
        }
    }

    /**
     * Define the values for certain environment constants from the plugin settings
     */
    protected static function defineEnvironmentConstantsFromCallbacks(): void
    {
        // All the environment variables to override
        $mappings = [
            [
                'class' => \PoPSchema\Comments\ComponentConfiguration::class,
                'envVariable' => \PoPSchema\Comments\Environment::MUST_USER_BE_LOGGED_IN_TO_ADD_COMMENT,
                'callback' => fn () => \get_option('comment_registration') === '1',
            ],
        ];
        foreach ($mappings as $mapping) {
            // If the environment value has been defined, or the constant in wp-config.php,
            // then do nothing, since they have priority
            $envVariable = $mapping['envVariable'];
            if (getenv($envVariable) !== false || PluginConfigurationHelpers::isWPConfigConstantDefined($envVariable)) {
                continue;
            }
            $hookName = ComponentConfigurationHelpers::getHookName(
                $mapping['class'],
                $envVariable
            );
            $callback = $mapping['callback'];
            \add_filter(
                $hookName,
                fn () => $callback(),
            );
        }
    }

    /**
     * Map the environment variables from the components, to WordPress wp-config.php constants
     */
    protected static function mapEnvVariablesToWPConfigConstants(): void
    {
        // All the environment variables to override
        $mappings = [
            [
                'class' => ComponentConfiguration::class,
                'envVariable' => Environment::ADD_EXCERPT_AS_DESCRIPTION,
            ],
            [
                'class' => GraphQLEndpointForWPComponentConfiguration::class,
                'envVariable' => GraphQLEndpointForWPEnvironment::GRAPHQL_API_ENDPOINT,
            ],
            [
                'class' => GraphQLClientsForWPComponentConfiguration::class,
                'envVariable' => GraphQLClientsForWPEnvironment::GRAPHIQL_CLIENT_ENDPOINT,
            ],
            [
                'class' => GraphQLClientsForWPComponentConfiguration::class,
                'envVariable' => GraphQLClientsForWPEnvironment::VOYAGER_CLIENT_ENDPOINT,
            ],
            [
                'class' => AccessControlComponentConfiguration::class,
                'envVariable' => AccessControlEnvironment::USE_PRIVATE_SCHEMA_MODE,
            ],
            [
                'class' => AccessControlComponentConfiguration::class,
                'envVariable' => AccessControlEnvironment::ENABLE_INDIVIDUAL_CONTROL_FOR_PUBLIC_PRIVATE_SCHEMA_MODE,
            ],
            [
                'class' => ComponentModelComponentConfiguration::class,
                'envVariable' => ComponentModelEnvironment::NAMESPACE_TYPES_AND_INTERFACES,
            ],
            [
                'class' => CacheControlComponentConfiguration::class,
                'envVariable' => CacheControlEnvironment::DEFAULT_CACHE_CONTROL_MAX_AGE,
            ],
            [
                'class' => ComponentModelComponentConfiguration::class,
                'envVariable' => ComponentModelEnvironment::ENABLE_ADMIN_SCHEMA,
            ],
        ];
        // For each environment variable, see if it has been defined as a wp-config.php constant
        foreach ($mappings as $mapping) {
            $class = $mapping['class'];
            $envVariable = $mapping['envVariable'];

            // If the environment value has been defined, then do nothing, since it has priority
            if (getenv($envVariable) !== false) {
                continue;
            }
            $hookName = ComponentConfigurationHelpers::getHookName(
                $class,
                $envVariable
            );

            \add_filter(
                $hookName,
                /**
                 * Override the value of an environment variable if it has been definedas a constant
                 * in wp-config.php, with the environment name prepended with "GRAPHQL_API_"
                 */
                function ($value) use ($envVariable) {
                    if (PluginConfigurationHelpers::isWPConfigConstantDefined($envVariable)) {
                        return PluginConfigurationHelpers::getWPConfigConstantValue($envVariable);
                    }
                    return $value;
                }
            );
        }
    }

    /**
     * Provide the configuration to cache the container
     *
     * @return array<mixed> Array with args to pass to `AppLoader::initializeContainers`:
     *                      [0]: cache container? (bool)
     *                      [1]: container namespace (string|null)
     *                      [2]: container directory (string|null)
     */
    public static function getContainerCacheConfiguration(): array
    {
        if (is_null(self::$containerCacheConfigurationCache)) {
            $containerConfigurationCacheNamespace = null;
            $containerConfigurationCacheDirectory = null;
            $mainPluginCacheDir = (string) MainPluginManager::getConfig('cache-dir');
            if ($cacheContainerConfiguration = PluginEnvironment::isCachingEnabled()) {
                $cacheConfigurationManager = CacheConfigurationManagerFacade::getInstance();
                $containerConfigurationCacheNamespace = $cacheConfigurationManager->getNamespace();
                $containerConfigurationCacheDirectory = $mainPluginCacheDir . \DIRECTORY_SEPARATOR . 'service-containers';
            }
            self::$containerCacheConfigurationCache = [
                $cacheContainerConfiguration,
                $containerConfigurationCacheNamespace,
                $containerConfigurationCacheDirectory
            ];
        }
        return self::$containerCacheConfigurationCache;
    }

    /**
     * Provide the configuration for all components required in the plugin
     *
     * @return array<string, array> [key]: Component class, [value]: Configuration
     */
    public static function getComponentClassConfiguration(): array
    {
        $componentClassConfiguration = [];
        self::addPredefinedComponentClassConfiguration($componentClassConfiguration);
        self::addBasedOnModuleEnabledStateComponentClassConfiguration($componentClassConfiguration);
        return $componentClassConfiguration;
    }

    /**
     * Add the fixed configuration for all components required in the plugin
     *
     * @param array<string, array> $componentClassConfiguration [key]: Component class, [value]: Configuration
     */
    protected static function addPredefinedComponentClassConfiguration(array &$componentClassConfiguration): void
    {
        $moduleRegistry = SystemModuleRegistryFacade::getInstance();
        $isDev = RootEnvironment::isApplicationEnvironmentDev();
        $mainPluginURL = (string) MainPluginManager::getConfig('url');

        $componentClassConfiguration[\PoP\ComponentModel\Component::class] = [
            /**
             * Enable the schema entity registries, as to retrieve the type/directive resolver classes
             * from the type/directive names, saved in the DB in the ACL/CCL Custom Post Types
             */
            ComponentModelEnvironment::ENABLE_SCHEMA_ENTITY_REGISTRIES => true,
            /**
             * Treat casting failures as errors, not warnings
             */
            ComponentModelEnvironment::TREAT_TYPE_COERCING_FAILURES_AS_ERRORS => true,
            /**
             * Show a `null` entry in the response for failing fields
             */
            ComponentModelEnvironment::SET_FAILING_FIELD_RESPONSE_AS_NULL => true,
        ];
        $componentClassConfiguration[\GraphQLByPoP\GraphQLClientsForWP\Component::class] = [
            \GraphQLByPoP\GraphQLClientsForWP\Environment::GRAPHQL_CLIENTS_COMPONENT_URL => $mainPluginURL . 'vendor/graphql-by-pop/graphql-clients-for-wp',
        ];
        $componentClassConfiguration[\PoP\APIEndpointsForWP\Component::class] = [
            // Disable the Native endpoint
            \PoP\APIEndpointsForWP\Environment::DISABLE_NATIVE_API_ENDPOINT => true,
        ];
        $componentClassConfiguration[\GraphQLByPoP\GraphQLRequest\Component::class] = [
            // Disable processing ?query=...
            \GraphQLByPoP\GraphQLRequest\Environment::DISABLE_GRAPHQL_API_FOR_POP => true,
            // Enable Multiple Query Execution?
            \GraphQLByPoP\GraphQLRequest\Environment::ENABLE_MULTIPLE_QUERY_EXECUTION => $moduleRegistry->isModuleEnabled(OperationalFunctionalityModuleResolver::MULTIPLE_QUERY_EXECUTION),
        ];
        // Cache the container
        if ($moduleRegistry->isModuleEnabled(CacheFunctionalityModuleResolver::CONFIGURATION_CACHE)) {
            $componentClassConfiguration[\PoP\Root\Component::class] = [
                RootEnvironment::THROW_EXCEPTION_IF_CACHE_SETUP_ERROR => $isDev,
            ];
        }
        $componentClassConfiguration[\GraphQLByPoP\GraphQLServer\Component::class] = [
            // Expose the "self" field when doing Low Level Query Editing
            GraphQLServerEnvironment::ADD_SELF_FIELD_FOR_ROOT_TYPE_TO_SCHEMA => $moduleRegistry->isModuleEnabled(UserInterfaceFunctionalityModuleResolver::LOW_LEVEL_PERSISTED_QUERY_EDITING),
            // Enable @removeIfNull?
            GraphQLServerEnvironment::ENABLE_REMOVE_IF_NULL_DIRECTIVE => $moduleRegistry->isModuleEnabled(OperationalFunctionalityModuleResolver::REMOVE_IF_NULL_DIRECTIVE),
            // Enable Proactive Feedback?
            GraphQLServerEnvironment::ENABLE_PROACTIVE_FEEDBACK => $moduleRegistry->isModuleEnabled(OperationalFunctionalityModuleResolver::PROACTIVE_FEEDBACK),
        ];
        $componentClassConfiguration[\PoP\API\Component::class] = [
            // Enable Embeddable Fields?
            \PoP\API\Environment::ENABLE_EMBEDDABLE_FIELDS => $moduleRegistry->isModuleEnabled(OperationalFunctionalityModuleResolver::EMBEDDABLE_FIELDS),
            // Enable Mutations?
            \PoP\API\Environment::ENABLE_MUTATIONS => $moduleRegistry->isModuleEnabled(SchemaTypeModuleResolver::SCHEMA_MUTATIONS),
        ];
        $componentClassConfiguration[\GraphQLByPoP\GraphQLQuery\Component::class] = [
            // Enable Composable Directives?
            GraphQLQueryEnvironment::ENABLE_COMPOSABLE_DIRECTIVES => $moduleRegistry->isModuleEnabled(OperationalFunctionalityModuleResolver::COMPOSABLE_DIRECTIVES),
        ];

        // If doing ?behavior=unrestricted, always enable certain features
        // Retrieve this service from the SystemContainer
        $systemInstanceManager = SystemInstanceManagerFacade::getInstance();
        /** @var EndpointHelpers */
        $endpointHelpers = $systemInstanceManager->getInstance(EndpointHelpers::class);
        if ($endpointHelpers->isRequestingAdminFixedSchemaGraphQLEndpoint()) {
            // Enable the "unrestricted" admin fields
            $componentClassConfiguration[\PoP\ComponentModel\Component::class][ComponentModelEnvironment::ENABLE_ADMIN_SCHEMA] = true;
            // Enable Nested mutations
            $componentClassConfiguration[\GraphQLByPoP\GraphQLServer\Component::class][GraphQLServerEnvironment::ENABLE_NESTED_MUTATIONS] = true;
            // Do not disable redundant mutation fields in the root type
            $componentClassConfiguration[\PoP\Engine\Component::class][EngineEnvironment::DISABLE_REDUNDANT_ROOT_TYPE_MUTATION_FIELDS] = false;
            // Allow access to all entries for Root.option
            $componentClassConfiguration[\PoPSchema\Settings\Component::class][SettingsEnvironment::SETTINGS_ENTRIES] = [];
            $componentClassConfiguration[\PoPSchema\Settings\Component::class][SettingsEnvironment::SETTINGS_BEHAVIOR] = Behaviors::DENYLIST;
            // Allow access to all meta values
            $componentClassConfiguration[\PoPSchema\CustomPostMeta\Component::class][CustomPostMetaEnvironment::CUSTOMPOST_META_ENTRIES] = [];
            $componentClassConfiguration[\PoPSchema\CustomPostMeta\Component::class][CustomPostMetaEnvironment::CUSTOMPOST_META_BEHAVIOR] = Behaviors::DENYLIST;
            $componentClassConfiguration[\PoPSchema\UserMeta\Component::class][UserMetaEnvironment::USER_META_ENTRIES] = [];
            $componentClassConfiguration[\PoPSchema\UserMeta\Component::class][UserMetaEnvironment::USER_META_BEHAVIOR] = Behaviors::DENYLIST;
            $componentClassConfiguration[\PoPSchema\CommentMeta\Component::class][CommentMetaEnvironment::COMMENT_META_ENTRIES] = [];
            $componentClassConfiguration[\PoPSchema\CommentMeta\Component::class][CommentMetaEnvironment::COMMENT_META_BEHAVIOR] = Behaviors::DENYLIST;
            $componentClassConfiguration[\PoPSchema\TaxonomyMeta\Component::class][TaxonomyMetaEnvironment::TAXONOMY_META_ENTRIES] = [];
            $componentClassConfiguration[\PoPSchema\TaxonomyMeta\Component::class][TaxonomyMetaEnvironment::TAXONOMY_META_BEHAVIOR] = Behaviors::DENYLIST;
        }
    }

    /**
     * Return the opposite value
     */
    protected static function opposite(bool $value): bool
    {
        return !$value;
    }

    /**
     * Add configuration values if modules are enabled or disabled
     *
     * @param array<string, array> $componentClassConfiguration [key]: Component class, [value]: Configuration
     */
    protected static function addBasedOnModuleEnabledStateComponentClassConfiguration(array &$componentClassConfiguration): void
    {
        $moduleRegistry = SystemModuleRegistryFacade::getInstance();
        $moduleToComponentClassConfigurationMappings = [
            [
                'module' => EndpointFunctionalityModuleResolver::SINGLE_ENDPOINT,
                'class' => \GraphQLByPoP\GraphQLEndpointForWP\Component::class,
                'envVariable' => \GraphQLByPoP\GraphQLEndpointForWP\Environment::DISABLE_GRAPHQL_API_ENDPOINT,
                'callback' => [self::class, 'opposite'],
            ],
            [
                'module' => ClientFunctionalityModuleResolver::GRAPHIQL_FOR_SINGLE_ENDPOINT,
                'class' => \GraphQLByPoP\GraphQLClientsForWP\Component::class,
                'envVariable' => \GraphQLByPoP\GraphQLClientsForWP\Environment::DISABLE_GRAPHIQL_CLIENT_ENDPOINT,
                'callback' => [self::class, 'opposite'],
            ],
            [
                'module' => ClientFunctionalityModuleResolver::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT,
                'class' => \GraphQLByPoP\GraphQLClientsForWP\Component::class,
                'envVariable' => \GraphQLByPoP\GraphQLClientsForWP\Environment::DISABLE_VOYAGER_CLIENT_ENDPOINT,
                'callback' => [self::class, 'opposite'],
            ],
            [
                'module' => ClientFunctionalityModuleResolver::GRAPHIQL_EXPLORER,
                'class' => \GraphQLByPoP\GraphQLClientsForWP\Component::class,
                'envVariable' => \GraphQLByPoP\GraphQLClientsForWP\Environment::USE_GRAPHIQL_EXPLORER,
            ],
            // Cache the component model configuration
            [
                'module' => CacheFunctionalityModuleResolver::CONFIGURATION_CACHE,
                'class' => \PoP\ComponentModel\Component::class,
                'envVariable' => ComponentModelEnvironment::USE_COMPONENT_MODEL_CACHE,
            ],
            // Cache the schema
            [
                'module' => CacheFunctionalityModuleResolver::SCHEMA_CACHE,
                'class' => \PoP\API\Component::class,
                'envVariable' => \PoP\API\Environment::USE_SCHEMA_DEFINITION_CACHE,
            ],
        ];
        foreach ($moduleToComponentClassConfigurationMappings as $mapping) {
            // Copy the state (enabled/disabled) to the component
            $value = $moduleRegistry->isModuleEnabled($mapping['module']);
            // Make explicit it can be null so that PHPStan level 3 doesn't fail
            $callback = $mapping['callback'] ?? null;
            if (!is_null($callback)) {
                $value = $callback($value);
            }
            $componentClassConfiguration[$mapping['class']][$mapping['envVariable']] = $value;
        }
    }

    /**
     * Provide the classes of the components whose
     * schema initialization must be skipped
     *
     * @return string[]
     */
    public static function getSkippingSchemaComponentClasses(): array
    {
        // If doing ?behavior=unrestricted, always enable all schema-type modules
        $systemInstanceManager = SystemInstanceManagerFacade::getInstance();
        /** @var EndpointHelpers */
        $endpointHelpers = $systemInstanceManager->getInstance(EndpointHelpers::class);
        if ($endpointHelpers->isRequestingAdminFixedSchemaGraphQLEndpoint()) {
            return [];
        }

        // Component classes enabled/disabled by module
        $maybeSkipSchemaModuleComponentClasses = [
            SchemaTypeModuleResolver::SCHEMA_CUSTOMPOSTS => [
                \PoPSchema\CustomPostMedia\Component::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_GENERIC_CUSTOMPOSTS => [
                \PoPSchema\GenericCustomPosts\Component::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_POSTS => [
                \PoPSchema\Posts\Component::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_COMMENTS => [
                \PoPSchema\Comments\Component::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_USERS => [
                \PoPSchema\Users\Component::class,
                \PoPSchema\UserState\Component::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_USER_ROLES => [
                \PoPSchema\UserRoles\Component::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_PAGES => [
                \PoPSchema\Pages\Component::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_MEDIA => [
                \PoPSchema\CustomPostMedia\Component::class,
                \PoPSchema\Media\Component::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_TAGS => [
                \PoPSchema\Tags\Component::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_POST_TAGS => [
                \PoPSchema\PostTags\Component::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_CATEGORIES => [
                \PoPSchema\Categories\Component::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_POST_CATEGORIES => [
                \PoPSchema\PostCategories\Component::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_CUSTOMPOST_META => [
                \PoPSchema\CustomPostMeta\Component::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_USER_META => [
                \PoPSchema\UserMeta\Component::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_COMMENT_META => [
                \PoPSchema\CommentMeta\Component::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_TAXONOMY_META => [
                \PoPSchema\TaxonomyMeta\Component::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_MENUS => [
                \PoPSchema\Menus\Component::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_SETTINGS => [
                \PoPSchema\Settings\Component::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_USER_STATE_MUTATIONS => [
                \PoPSchema\UserStateMutations\Component::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_POST_MUTATIONS => [
                \PoPSchema\PostMutations\Component::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_CUSTOMPOSTMEDIA_MUTATIONS => [
                \PoPSchema\CustomPostMediaMutations\Component::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_POST_TAG_MUTATIONS => [
                \PoPSchema\PostTagMutations\Component::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_POST_CATEGORY_MUTATIONS => [
                \PoPSchema\PostCategoryMutations\Component::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_COMMENT_MUTATIONS => [
                \PoPSchema\CommentMutations\Component::class,
            ],
        ];
        $moduleRegistry = SystemModuleRegistryFacade::getInstance();
        $skipSchemaModuleComponentClasses = array_filter(
            $maybeSkipSchemaModuleComponentClasses,
            fn ($module) => !$moduleRegistry->isModuleEnabled($module),
            ARRAY_FILTER_USE_KEY
        );
        return GeneralUtils::arrayFlatten(
            array_values(
                $skipSchemaModuleComponentClasses
            )
        );
    }
}
