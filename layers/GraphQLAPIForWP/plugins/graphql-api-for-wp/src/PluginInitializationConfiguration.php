<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI;

use GraphQLAPI\GraphQLAPI\Constants\ModuleSettingOptions;
use GraphQLAPI\GraphQLAPI\Facades\Registries\SystemModuleRegistryFacade;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ClientFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\MetaSchemaTypeModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\MutationSchemaTypeModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\PerformanceFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaTypeModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\UserInterfaceFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\PluginManagement\PluginOptionsFormHandler;
use GraphQLAPI\GraphQLAPI\PluginSkeleton\AbstractMainPluginInitializationConfiguration;
use GraphQLAPI\GraphQLAPI\Services\Helpers\EndpointHelpers;
use GraphQLByPoP\GraphQLClientsForWP\Module as GraphQLClientsForWPModule;
use GraphQLByPoP\GraphQLClientsForWP\Environment as GraphQLClientsForWPEnvironment;
use GraphQLByPoP\GraphQLEndpointForWP\Module as GraphQLEndpointForWPModule;
use GraphQLByPoP\GraphQLEndpointForWP\Environment as GraphQLEndpointForWPEnvironment;
use GraphQLByPoP\GraphQLServer\Module as GraphQLServerModule;
use GraphQLByPoP\GraphQLServer\Configuration\MutationSchemes;
use GraphQLByPoP\GraphQLServer\Environment as GraphQLServerEnvironment;
use PoP\AccessControl\Module as AccessControlModule;
use PoP\AccessControl\Environment as AccessControlEnvironment;
use PoP\AccessControl\Schema\SchemaModes;
use PoP\CacheControl\Module as CacheControlModule;
use PoP\CacheControl\Environment as CacheControlEnvironment;
use PoP\ComponentModel\Module as ComponentModelModule;
use PoP\ComponentModel\Environment as ComponentModelEnvironment;
use PoP\Root\Environment as RootEnvironment;
use PoP\Root\Facades\Instances\SystemInstanceManagerFacade;
use PoP\Engine\Module as EngineModule;
use PoP\Engine\Environment as EngineEnvironment;
use PoPCMSSchema\Categories\Module as CategoriesModule;
use PoPCMSSchema\Categories\Environment as CategoriesEnvironment;
use PoPCMSSchema\CommentMeta\Module as CommentMetaModule;
use PoPCMSSchema\CommentMeta\Environment as CommentMetaEnvironment;
use PoPCMSSchema\Comments\Module as CommentsModule;
use PoPCMSSchema\Comments\Environment as CommentsEnvironment;
use PoPCMSSchema\CustomPostMeta\Module as CustomPostMetaModule;
use PoPCMSSchema\CustomPostMeta\Environment as CustomPostMetaEnvironment;
use PoPCMSSchema\CustomPosts\Module as CustomPostsModule;
use PoPCMSSchema\CustomPosts\Environment as CustomPostsEnvironment;
use PoPCMSSchema\GenericCustomPosts\Module as GenericCustomPostsModule;
use PoPCMSSchema\GenericCustomPosts\Environment as GenericCustomPostsEnvironment;
use PoPCMSSchema\Media\Module as MediaModule;
use PoPCMSSchema\Media\Environment as MediaEnvironment;
use PoPCMSSchema\Menus\Module as MenusModule;
use PoPCMSSchema\Menus\Environment as MenusEnvironment;
use PoPCMSSchema\Pages\Module as PagesModule;
use PoPCMSSchema\Pages\Environment as PagesEnvironment;
use PoPCMSSchema\Posts\Module as PostsModule;
use PoPCMSSchema\Posts\Environment as PostsEnvironment;
use PoPSchema\SchemaCommons\Constants\Behaviors;
use PoPCMSSchema\Settings\Module as SettingsModule;
use PoPCMSSchema\Settings\Environment as SettingsEnvironment;
use PoPCMSSchema\Tags\Module as TagsModule;
use PoPCMSSchema\Tags\Environment as TagsEnvironment;
use PoPCMSSchema\TaxonomyMeta\Module as TaxonomyMetaModule;
use PoPCMSSchema\TaxonomyMeta\Environment as TaxonomyMetaEnvironment;
use PoPCMSSchema\UserRoles\Module as UserRolesComponent;
use PoPCMSSchema\UserRoles\Environment as UserRolesEnvironment;
use PoPCMSSchema\UserMeta\Module as UserMetaComponent;
use PoPCMSSchema\UserAvatars\Module as UserAvatarsComponent;
use PoPCMSSchema\UserAvatars\Environment as UserAvatarsEnvironment;
use PoPCMSSchema\UserMeta\Environment as UserMetaEnvironment;
use PoPCMSSchema\Users\Module as UsersModule;
use PoPCMSSchema\Users\Environment as UsersEnvironment;

/**
 * Sets the configuration in all the PoP components from the main plugin.
 */
class PluginInitializationConfiguration extends AbstractMainPluginInitializationConfiguration
{
    protected function isCachingEnabled(): bool
    {
        return PluginEnvironment::isCachingEnabled();
    }

    /**
     * Define the values for certain environment constants from the plugin settings
     */
    protected function getEnvironmentConstantsFromSettingsMapping(): array
    {
        $moduleRegistry = SystemModuleRegistryFacade::getInstance();
        $systemInstanceManager = SystemInstanceManagerFacade::getInstance();
        /** @var EndpointHelpers */
        $endpointHelpers = $systemInstanceManager->getInstance(EndpointHelpers::class);
        // Get the possible states of wp-admin clients requesting the endpoint:
        // 1. Only GraphiQL and Voyager clients
        $isRequestingGraphQLEndpointForAdminClientOnly = $endpointHelpers->isRequestingGraphQLEndpointForAdminClientOnly();
        // 2. GraphiQL and Voyager clients + ACL/CCL configurations
        $isRequestingGraphQLEndpointForAdminClientOrConfiguration = $endpointHelpers->isRequestingGraphQLEndpointForAdminClientOrConfiguration();
        $pluginOptionsFormHandler = new PluginOptionsFormHandler();
        return [
            // GraphQL single endpoint slug
            [
                'class' => GraphQLEndpointForWPModule::class,
                'envVariable' => GraphQLEndpointForWPEnvironment::GRAPHQL_API_ENDPOINT,
                'module' => EndpointFunctionalityModuleResolver::SINGLE_ENDPOINT,
                'option' => ModuleSettingOptions::PATH,
                'callback' => fn ($value) => $pluginOptionsFormHandler->getURLPathSettingValue(
                    $value,
                    EndpointFunctionalityModuleResolver::SINGLE_ENDPOINT,
                    ModuleSettingOptions::PATH
                ),
                'condition' => 'any',
            ],
            // Custom Endpoint path
            [
                'class' => Module::class,
                'envVariable' => Environment::ENDPOINT_SLUG_BASE,
                'module' => EndpointFunctionalityModuleResolver::CUSTOM_ENDPOINTS,
                'option' => ModuleSettingOptions::PATH,
                'callback' => fn ($value) => $pluginOptionsFormHandler->getCPTPermalinkBasePathSettingValue(
                    $value,
                    EndpointFunctionalityModuleResolver::CUSTOM_ENDPOINTS,
                    ModuleSettingOptions::PATH
                ),
                'condition' => 'any',
            ],
            // Persisted Query path
            [
                'class' => Module::class,
                'envVariable' => Environment::PERSISTED_QUERY_SLUG_BASE,
                'module' => EndpointFunctionalityModuleResolver::PERSISTED_QUERIES,
                'option' => ModuleSettingOptions::PATH,
                'callback' => fn ($value) => $pluginOptionsFormHandler->getCPTPermalinkBasePathSettingValue(
                    $value,
                    EndpointFunctionalityModuleResolver::PERSISTED_QUERIES,
                    ModuleSettingOptions::PATH
                ),
                'condition' => 'any',
            ],
            // GraphiQL client slug
            [
                'class' => GraphQLClientsForWPModule::class,
                'envVariable' => GraphQLClientsForWPEnvironment::GRAPHIQL_CLIENT_ENDPOINT,
                'module' => ClientFunctionalityModuleResolver::GRAPHIQL_FOR_SINGLE_ENDPOINT,
                'option' => ModuleSettingOptions::PATH,
                'callback' => fn ($value) => $pluginOptionsFormHandler->getURLPathSettingValue(
                    $value,
                    ClientFunctionalityModuleResolver::GRAPHIQL_FOR_SINGLE_ENDPOINT,
                    ModuleSettingOptions::PATH
                ),
                'condition' => 'any',
            ],
            // Voyager client slug
            [
                'class' => GraphQLClientsForWPModule::class,
                'envVariable' => GraphQLClientsForWPEnvironment::VOYAGER_CLIENT_ENDPOINT,
                'module' => ClientFunctionalityModuleResolver::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT,
                'option' => ModuleSettingOptions::PATH,
                'callback' => fn ($value) => $pluginOptionsFormHandler->getURLPathSettingValue(
                    $value,
                    ClientFunctionalityModuleResolver::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT,
                    ModuleSettingOptions::PATH
                ),
                'condition' => 'any',
            ],
            // Use private schema mode?
            [
                'class' => AccessControlModule::class,
                'envVariable' => AccessControlEnvironment::USE_PRIVATE_SCHEMA_MODE,
                'module' => SchemaConfigurationFunctionalityModuleResolver::PUBLIC_PRIVATE_SCHEMA,
                'option' => SchemaConfigurationFunctionalityModuleResolver::OPTION_MODE,
                // It is stored as string "private" in DB, and must be passed as bool `true` to component
                'callback' => fn ($value) => $value == SchemaModes::PRIVATE_SCHEMA_MODE,
            ],
            // Enable individual access control for the schema mode?
            [
                'class' => AccessControlModule::class,
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
                'class' => ComponentModelModule::class,
                'envVariable' => ComponentModelEnvironment::NAMESPACE_TYPES_AND_INTERFACES,
                'module' => SchemaConfigurationFunctionalityModuleResolver::SCHEMA_NAMESPACING,
                'option' => $isRequestingGraphQLEndpointForAdminClientOrConfiguration ? ModuleSettingOptions::VALUE_FOR_ADMIN_CLIENTS : ModuleSettingOptions::DEFAULT_VALUE,
            ],
            // Enable nested mutations?
            // Only assign for Admin clients. For configuration it is assigned always, via the Fixed endpoint
            [
                'class' => GraphQLServerModule::class,
                'envVariable' => GraphQLServerEnvironment::ENABLE_NESTED_MUTATIONS,
                'module' => SchemaConfigurationFunctionalityModuleResolver::NESTED_MUTATIONS,
                'option' => $isRequestingGraphQLEndpointForAdminClientOnly ? ModuleSettingOptions::VALUE_FOR_ADMIN_CLIENTS : ModuleSettingOptions::DEFAULT_VALUE,
                'callback' => fn ($value) => $moduleRegistry->isModuleEnabled(SchemaConfigurationFunctionalityModuleResolver::NESTED_MUTATIONS) && $value != MutationSchemes::STANDARD,
            ],
            // Disable redundant mutation fields in the root type?
            [
                'class' => EngineModule::class,
                'envVariable' => EngineEnvironment::DISABLE_REDUNDANT_ROOT_TYPE_MUTATION_FIELDS,
                'module' => SchemaConfigurationFunctionalityModuleResolver::NESTED_MUTATIONS,
                'option' => $isRequestingGraphQLEndpointForAdminClientOnly ? ModuleSettingOptions::VALUE_FOR_ADMIN_CLIENTS : ModuleSettingOptions::DEFAULT_VALUE,
                'callback' => fn ($value) => $moduleRegistry->isModuleEnabled(SchemaConfigurationFunctionalityModuleResolver::NESTED_MUTATIONS) && $value == MutationSchemes::NESTED_WITHOUT_REDUNDANT_ROOT_FIELDS,
            ],
            // Cache-Control default max-age
            [
                'class' => CacheControlModule::class,
                'envVariable' => CacheControlEnvironment::DEFAULT_CACHE_CONTROL_MAX_AGE,
                'module' => PerformanceFunctionalityModuleResolver::CACHE_CONTROL,
                'option' => PerformanceFunctionalityModuleResolver::OPTION_MAX_AGE,
            ],
            // Custom Post default/max limits, Supported custom post types
            [
                'class' => GenericCustomPostsModule::class,
                'envVariable' => GenericCustomPostsEnvironment::GENERIC_CUSTOMPOST_LIST_DEFAULT_LIMIT,
                'module' => SchemaTypeModuleResolver::SCHEMA_GENERIC_CUSTOMPOSTS,
                'optionModule' => SchemaTypeModuleResolver::SCHEMA_CUSTOMPOSTS,
                'option' => ModuleSettingOptions::LIST_DEFAULT_LIMIT,
            ],
            [
                'class' => GenericCustomPostsModule::class,
                'envVariable' => GenericCustomPostsEnvironment::GENERIC_CUSTOMPOST_LIST_MAX_LIMIT,
                'module' => SchemaTypeModuleResolver::SCHEMA_GENERIC_CUSTOMPOSTS,
                'optionModule' => SchemaTypeModuleResolver::SCHEMA_CUSTOMPOSTS,
                'option' => ModuleSettingOptions::LIST_MAX_LIMIT,
            ],
            [
                'class' => GenericCustomPostsModule::class,
                'envVariable' => GenericCustomPostsEnvironment::GENERIC_CUSTOMPOST_TYPES,
                'module' => SchemaTypeModuleResolver::SCHEMA_GENERIC_CUSTOMPOSTS,
                'option' => ModuleSettingOptions::CUSTOMPOST_TYPES,
            ],
            // Post default/max limits, add to CustomPostUnion
            [
                'class' => PostsModule::class,
                'envVariable' => PostsEnvironment::POST_LIST_DEFAULT_LIMIT,
                'module' => SchemaTypeModuleResolver::SCHEMA_POSTS,
                'optionModule' => SchemaTypeModuleResolver::SCHEMA_POSTS,
                'option' => ModuleSettingOptions::LIST_DEFAULT_LIMIT,
            ],
            [
                'class' => PostsModule::class,
                'envVariable' => PostsEnvironment::POST_LIST_MAX_LIMIT,
                'module' => SchemaTypeModuleResolver::SCHEMA_POSTS,
                'optionModule' => SchemaTypeModuleResolver::SCHEMA_POSTS,
                'option' => ModuleSettingOptions::LIST_MAX_LIMIT,
            ],
            [
                'class' => PostsModule::class,
                'envVariable' => PostsEnvironment::ADD_POST_TYPE_TO_CUSTOMPOST_UNION_TYPES,
                'module' => SchemaTypeModuleResolver::SCHEMA_POSTS,
                'option' => ModuleSettingOptions::ADD_TYPE_TO_CUSTOMPOST_UNION_TYPE,
            ],
            // User default/max limits
            [
                'class' => UsersModule::class,
                'envVariable' => UsersEnvironment::USER_LIST_DEFAULT_LIMIT,
                'module' => SchemaTypeModuleResolver::SCHEMA_USERS,
                'option' => ModuleSettingOptions::LIST_DEFAULT_LIMIT,
            ],
            [
                'class' => UsersModule::class,
                'envVariable' => UsersEnvironment::USER_LIST_MAX_LIMIT,
                'module' => SchemaTypeModuleResolver::SCHEMA_USERS,
                'option' => ModuleSettingOptions::LIST_MAX_LIMIT,
            ],
            [
                'class' => UsersModule::class,
                'envVariable' => UsersEnvironment::TREAT_USER_EMAIL_AS_ADMIN_DATA,
                'module' => SchemaTypeModuleResolver::SCHEMA_USERS,
                'option' => SchemaTypeModuleResolver::OPTION_TREAT_USER_EMAIL_AS_ADMIN_DATA,
            ],
            // Comment default/max limits
            [
                'class' => CommentsModule::class,
                'envVariable' => CommentsEnvironment::ROOT_COMMENT_LIST_DEFAULT_LIMIT,
                'module' => SchemaTypeModuleResolver::SCHEMA_COMMENTS,
                'option' => SchemaTypeModuleResolver::OPTION_ROOT_COMMENT_LIST_DEFAULT_LIMIT,
            ],
            [
                'class' => CommentsModule::class,
                'envVariable' => CommentsEnvironment::CUSTOMPOST_COMMENT_OR_COMMENT_RESPONSE_LIST_DEFAULT_LIMIT,
                'module' => SchemaTypeModuleResolver::SCHEMA_COMMENTS,
                'option' => SchemaTypeModuleResolver::OPTION_CUSTOMPOST_COMMENT_OR_COMMENT_RESPONSE_LIST_DEFAULT_LIMIT,
            ],
            [
                'class' => CommentsModule::class,
                'envVariable' => CommentsEnvironment::COMMENT_LIST_MAX_LIMIT,
                'module' => SchemaTypeModuleResolver::SCHEMA_COMMENTS,
                'option' => ModuleSettingOptions::LIST_MAX_LIMIT,
            ],
            [
                'class' => CommentsModule::class,
                'envVariable' => CommentsEnvironment::TREAT_COMMENT_STATUS_AS_ADMIN_DATA,
                'module' => SchemaTypeModuleResolver::SCHEMA_COMMENTS,
                'option' => SchemaTypeModuleResolver::OPTION_TREAT_COMMENT_STATUS_AS_ADMIN_DATA,
            ],
            // Media default/max limits
            [
                'class' => MediaModule::class,
                'envVariable' => MediaEnvironment::MEDIA_LIST_DEFAULT_LIMIT,
                'module' => SchemaTypeModuleResolver::SCHEMA_MEDIA,
                'option' => ModuleSettingOptions::LIST_DEFAULT_LIMIT,
            ],
            [
                'class' => MediaModule::class,
                'envVariable' => MediaEnvironment::MEDIA_LIST_MAX_LIMIT,
                'module' => SchemaTypeModuleResolver::SCHEMA_MEDIA,
                'option' => ModuleSettingOptions::LIST_MAX_LIMIT,
            ],
            // Menu default/max limits
            [
                'class' => MenusModule::class,
                'envVariable' => MenusEnvironment::MENU_LIST_DEFAULT_LIMIT,
                'module' => SchemaTypeModuleResolver::SCHEMA_MENUS,
                'optionModule' => SchemaTypeModuleResolver::SCHEMA_MENUS,
                'option' => ModuleSettingOptions::LIST_DEFAULT_LIMIT,
            ],
            [
                'class' => MenusModule::class,
                'envVariable' => MenusEnvironment::MENU_LIST_MAX_LIMIT,
                'module' => SchemaTypeModuleResolver::SCHEMA_MENUS,
                'optionModule' => SchemaTypeModuleResolver::SCHEMA_MENUS,
                'option' => ModuleSettingOptions::LIST_MAX_LIMIT,
            ],
            // Tag default/max limits
            [
                'class' => TagsModule::class,
                'envVariable' => TagsEnvironment::TAG_LIST_DEFAULT_LIMIT,
                'module' => SchemaTypeModuleResolver::SCHEMA_TAGS,
                'option' => ModuleSettingOptions::LIST_DEFAULT_LIMIT,
            ],
            [
                'class' => TagsModule::class,
                'envVariable' => TagsEnvironment::TAG_LIST_MAX_LIMIT,
                'module' => SchemaTypeModuleResolver::SCHEMA_TAGS,
                'option' => ModuleSettingOptions::LIST_MAX_LIMIT,
            ],
            // Categories default/max limits
            [
                'class' => CategoriesModule::class,
                'envVariable' => CategoriesEnvironment::CATEGORY_LIST_DEFAULT_LIMIT,
                'module' => SchemaTypeModuleResolver::SCHEMA_CATEGORIES,
                'option' => ModuleSettingOptions::LIST_DEFAULT_LIMIT,
            ],
            [
                'class' => CategoriesModule::class,
                'envVariable' => CategoriesEnvironment::CATEGORY_LIST_MAX_LIMIT,
                'module' => SchemaTypeModuleResolver::SCHEMA_CATEGORIES,
                'option' => ModuleSettingOptions::LIST_MAX_LIMIT,
            ],
            // Page default/max limits, add to CustomPostUnion
            [
                'class' => PagesModule::class,
                'envVariable' => PagesEnvironment::PAGE_LIST_DEFAULT_LIMIT,
                'module' => SchemaTypeModuleResolver::SCHEMA_PAGES,
                'optionModule' => SchemaTypeModuleResolver::SCHEMA_PAGES,
                'option' => ModuleSettingOptions::LIST_DEFAULT_LIMIT,
            ],
            [
                'class' => PagesModule::class,
                'envVariable' => PagesEnvironment::PAGE_LIST_MAX_LIMIT,
                'module' => SchemaTypeModuleResolver::SCHEMA_PAGES,
                'optionModule' => SchemaTypeModuleResolver::SCHEMA_PAGES,
                'option' => ModuleSettingOptions::LIST_MAX_LIMIT,
            ],
            [
                'class' => PagesModule::class,
                'envVariable' => PagesEnvironment::ADD_PAGE_TYPE_TO_CUSTOMPOST_UNION_TYPES,
                'module' => SchemaTypeModuleResolver::SCHEMA_PAGES,
                'option' => ModuleSettingOptions::ADD_TYPE_TO_CUSTOMPOST_UNION_TYPE,
            ],
            // Custom post default/max limits
            [
                'class' => CustomPostsModule::class,
                'envVariable' => CustomPostsEnvironment::CUSTOMPOST_LIST_DEFAULT_LIMIT,
                'module' => SchemaTypeModuleResolver::SCHEMA_CUSTOMPOSTS,
                'option' => ModuleSettingOptions::LIST_DEFAULT_LIMIT,
            ],
            [
                'class' => CustomPostsModule::class,
                'envVariable' => CustomPostsEnvironment::CUSTOMPOST_LIST_MAX_LIMIT,
                'module' => SchemaTypeModuleResolver::SCHEMA_CUSTOMPOSTS,
                'option' => ModuleSettingOptions::LIST_MAX_LIMIT,
            ],
            [
                'class' => CustomPostsModule::class,
                'envVariable' => CustomPostsEnvironment::TREAT_CUSTOMPOST_STATUS_AS_ADMIN_DATA,
                'module' => SchemaTypeModuleResolver::SCHEMA_CUSTOMPOSTS,
                'option' => SchemaTypeModuleResolver::OPTION_TREAT_CUSTOMPOST_STATUS_AS_ADMIN_DATA,
            ],
            // Custom post, if there is only one custom type, use it instead of the Union
            [
                'class' => CustomPostsModule::class,
                'envVariable' => CustomPostsEnvironment::USE_SINGLE_TYPE_INSTEAD_OF_CUSTOMPOST_UNION_TYPE,
                'module' => SchemaTypeModuleResolver::SCHEMA_CUSTOMPOSTS,
                'option' => SchemaTypeModuleResolver::OPTION_USE_SINGLE_TYPE_INSTEAD_OF_UNION_TYPE,
            ],
            // White/Blacklisted entries to Root.option
            [
                'class' => SettingsModule::class,
                'envVariable' => SettingsEnvironment::SETTINGS_ENTRIES,
                'module' => SchemaTypeModuleResolver::SCHEMA_SETTINGS,
                'option' => ModuleSettingOptions::ENTRIES,
                // Remove whitespaces, and empty entries (they mess up with regex)
                'callback' => fn (array $value) => array_filter(array_map(trim(...), $value)),
            ],
            [
                'class' => SettingsModule::class,
                'envVariable' => SettingsEnvironment::SETTINGS_BEHAVIOR,
                'module' => SchemaTypeModuleResolver::SCHEMA_SETTINGS,
                'option' => ModuleSettingOptions::BEHAVIOR,
            ],
            // Enable the "admin" schema: if doing ?behavior=unrestricted, it will already
            // be set by configuration. Otherwise, it uses this mapping
            [
                'class' => ComponentModelModule::class,
                'envVariable' => ComponentModelEnvironment::ENABLE_ADMIN_SCHEMA,
                'module' => SchemaTypeModuleResolver::SCHEMA_EXPOSE_ADMIN_DATA,
                'option' => $isRequestingGraphQLEndpointForAdminClientOnly ? ModuleSettingOptions::VALUE_FOR_ADMIN_CLIENTS : ModuleSettingOptions::DEFAULT_VALUE,
            ],
            // White/Blacklisted entries to CustomPost.meta
            [
                'class' => CustomPostMetaModule::class,
                'envVariable' => CustomPostMetaEnvironment::CUSTOMPOST_META_ENTRIES,
                'module' => MetaSchemaTypeModuleResolver::SCHEMA_CUSTOMPOST_META,
                'option' => ModuleSettingOptions::ENTRIES,
                // Remove whitespaces, and empty entries (they mess up with regex)
                'callback' => fn (array $value) => array_filter(array_map(trim(...), $value)),
            ],
            [
                'class' => CustomPostMetaModule::class,
                'envVariable' => CustomPostMetaEnvironment::CUSTOMPOST_META_BEHAVIOR,
                'module' => MetaSchemaTypeModuleResolver::SCHEMA_CUSTOMPOST_META,
                'option' => ModuleSettingOptions::BEHAVIOR,
            ],
            // White/Blacklisted entries to User.meta
            [
                'class' => UserMetaComponent::class,
                'envVariable' => UserMetaEnvironment::USER_META_ENTRIES,
                'module' => MetaSchemaTypeModuleResolver::SCHEMA_USER_META,
                'option' => ModuleSettingOptions::ENTRIES,
                // Remove whitespaces, and empty entries (they mess up with regex)
                'callback' => fn (array $value) => array_filter(array_map(trim(...), $value)),
            ],
            [
                'class' => UserMetaComponent::class,
                'envVariable' => UserMetaEnvironment::USER_META_BEHAVIOR,
                'module' => MetaSchemaTypeModuleResolver::SCHEMA_USER_META,
                'option' => ModuleSettingOptions::BEHAVIOR,
            ],
            // White/Blacklisted entries to Comment.meta
            [
                'class' => CommentMetaModule::class,
                'envVariable' => CommentMetaEnvironment::COMMENT_META_ENTRIES,
                'module' => MetaSchemaTypeModuleResolver::SCHEMA_COMMENT_META,
                'option' => ModuleSettingOptions::ENTRIES,
                // Remove whitespaces, and empty entries (they mess up with regex)
                'callback' => fn (array $value) => array_filter(array_map(trim(...), $value)),
            ],
            [
                'class' => CommentMetaModule::class,
                'envVariable' => CommentMetaEnvironment::COMMENT_META_BEHAVIOR,
                'module' => MetaSchemaTypeModuleResolver::SCHEMA_COMMENT_META,
                'option' => ModuleSettingOptions::BEHAVIOR,
            ],
            // White/Blacklisted entries to PostTag.meta and PostCategory.meta
            [
                'class' => TaxonomyMetaModule::class,
                'envVariable' => TaxonomyMetaEnvironment::TAXONOMY_META_ENTRIES,
                'module' => MetaSchemaTypeModuleResolver::SCHEMA_TAXONOMY_META,
                'option' => ModuleSettingOptions::ENTRIES,
                // Remove whitespaces, and empty entries (they mess up with regex)
                'callback' => fn (array $value) => array_filter(array_map(trim(...), $value)),
            ],
            [
                'class' => TaxonomyMetaModule::class,
                'envVariable' => TaxonomyMetaEnvironment::TAXONOMY_META_BEHAVIOR,
                'module' => MetaSchemaTypeModuleResolver::SCHEMA_TAXONOMY_META,
                'option' => ModuleSettingOptions::BEHAVIOR,
            ],
            [
                'class' => UserAvatarsComponent::class,
                'envVariable' => UserAvatarsEnvironment::USER_AVATAR_DEFAULT_SIZE,
                'module' => SchemaTypeModuleResolver::SCHEMA_USER_AVATARS,
                'option' => SchemaTypeModuleResolver::OPTION_DEFAULT_AVATAR_SIZE,
            ],
            [
                'class' => UserRolesComponent::class,
                'envVariable' => UserRolesEnvironment::TREAT_USER_ROLE_AS_ADMIN_DATA,
                'module' => SchemaTypeModuleResolver::SCHEMA_USER_ROLES,
                'option' => SchemaTypeModuleResolver::OPTION_TREAT_USER_ROLE_AS_ADMIN_DATA,
            ],
            [
                'class' => UserRolesComponent::class,
                'envVariable' => UserRolesEnvironment::TREAT_USER_CAPABILITY_AS_ADMIN_DATA,
                'module' => SchemaTypeModuleResolver::SCHEMA_USER_ROLES,
                'option' => SchemaTypeModuleResolver::OPTION_TREAT_USER_CAPABILITY_AS_ADMIN_DATA,
            ],
        ];
    }

    /**
     * Define the values for certain environment constants from the plugin settings
     */
    protected function getEnvironmentConstantsFromCallbacksMapping(): array
    {
        return [
            [
                'class' => \PoPCMSSchema\CommentMutations\Module::class,
                'envVariable' => \PoPCMSSchema\CommentMutations\Environment::MUST_USER_BE_LOGGED_IN_TO_ADD_COMMENT,
                'callback' => fn () => \get_option('comment_registration') === '1',
            ],
            [
                'class' => \PoPCMSSchema\CommentMutations\Module::class,
                'envVariable' => \PoPCMSSchema\CommentMutations\Environment::REQUIRE_COMMENTER_NAME_AND_EMAIL,
                'callback' => fn () => \get_option('require_name_email') === '1',
            ],
        ];
    }

    /**
     * All the environment variables to override
     */
    protected function getEnvVariablesToWPConfigConstantsMapping(): array
    {
        return [
            [
                'class' => Module::class,
                'envVariable' => Environment::ADD_EXCERPT_AS_DESCRIPTION,
            ],
            [
                'class' => GraphQLEndpointForWPModule::class,
                'envVariable' => GraphQLEndpointForWPEnvironment::GRAPHQL_API_ENDPOINT,
            ],
            [
                'class' => GraphQLClientsForWPModule::class,
                'envVariable' => GraphQLClientsForWPEnvironment::GRAPHIQL_CLIENT_ENDPOINT,
            ],
            [
                'class' => GraphQLClientsForWPModule::class,
                'envVariable' => GraphQLClientsForWPEnvironment::VOYAGER_CLIENT_ENDPOINT,
            ],
            [
                'class' => AccessControlModule::class,
                'envVariable' => AccessControlEnvironment::USE_PRIVATE_SCHEMA_MODE,
            ],
            [
                'class' => AccessControlModule::class,
                'envVariable' => AccessControlEnvironment::ENABLE_INDIVIDUAL_CONTROL_FOR_PUBLIC_PRIVATE_SCHEMA_MODE,
            ],
            [
                'class' => ComponentModelModule::class,
                'envVariable' => ComponentModelEnvironment::NAMESPACE_TYPES_AND_INTERFACES,
            ],
            [
                'class' => CacheControlModule::class,
                'envVariable' => CacheControlEnvironment::DEFAULT_CACHE_CONTROL_MAX_AGE,
            ],
            [
                'class' => ComponentModelModule::class,
                'envVariable' => ComponentModelEnvironment::ENABLE_ADMIN_SCHEMA,
            ],
        ];
    }

    /**
     * Get the fixed configuration for all components required in the plugin
     *
     * @return array<string, array> [key]: Module class, [value]: Configuration
     */
    protected function getPredefinedComponentClassConfiguration(): array
    {
        $moduleRegistry = SystemModuleRegistryFacade::getInstance();
        $mainPluginURL = App::getMainPlugin()->getPluginURL();

        $componentClassConfiguration = [];
        $componentClassConfiguration[\PoP\Root\Module::class] = [
            /**
             * Can pass state for "variables" and "actions"
             */
            RootEnvironment::ENABLE_PASSING_STATE_VIA_REQUEST => true,
        ];
        $componentClassConfiguration[\PoP\ComponentModel\Module::class] = [
            /**
             * Treat casting failures as errors, not warnings
             */
            ComponentModelEnvironment::TREAT_TYPE_COERCING_FAILURES_AS_ERRORS => true,
            /**
             * Treat querying for an undefined field/directive arg as an error, not a warning
             */
            ComponentModelEnvironment::TREAT_UNDEFINED_FIELD_OR_DIRECTIVE_ARGS_AS_ERRORS => true,
            /**
             * Show a `null` entry in the response for failing fields
             */
            ComponentModelEnvironment::SET_FAILING_FIELD_RESPONSE_AS_NULL => true,
            /**
             * If a directive fails, then remove the affected IDs/fields from the upcoming stages of the directive pipeline execution
             */
            ComponentModelEnvironment::REMOVE_FIELD_IF_DIRECTIVE_FAILED => true,
            /**
             * Do not expose the `DangerouslyNonSpecificScalar` scalar type
             */
            ComponentModelEnvironment::SKIP_EXPOSING_DANGEROUSLY_NON_SPECIFIC_SCALAR_TYPE_IN_SCHEMA => true,
            /**
             * Enable Mutations?
             */
            ComponentModelEnvironment::ENABLE_MUTATIONS => $moduleRegistry->isModuleEnabled(MutationSchemaTypeModuleResolver::SCHEMA_MUTATIONS),
        ];
        $componentClassConfiguration[\GraphQLByPoP\GraphQLClientsForWP\Module::class] = [
            \GraphQLByPoP\GraphQLClientsForWP\Environment::GRAPHQL_CLIENTS_COMPONENT_URL => $mainPluginURL . 'vendor/graphql-by-pop/graphql-clients-for-wp',
        ];
        $componentClassConfiguration[\PoPAPI\APIEndpointsForWP\Module::class] = [
            // Disable the Native endpoint
            \PoPAPI\APIEndpointsForWP\Environment::DISABLE_NATIVE_API_ENDPOINT => true,
        ];
        $componentClassConfiguration[\GraphQLByPoP\GraphQLServer\Module::class] = [
            // Expose the "self" field when doing Low Level Query Editing
            GraphQLServerEnvironment::EXPOSE_SELF_FIELD_FOR_ROOT_TYPE_IN_GRAPHQL_SCHEMA => $moduleRegistry->isModuleEnabled(UserInterfaceFunctionalityModuleResolver::LOW_LEVEL_PERSISTED_QUERY_EDITING),
            // Do not send proactive deprecations
            GraphQLServerEnvironment::ENABLE_PROACTIVE_FEEDBACK_DEPRECATIONS => false,
        ];
        $componentClassConfiguration[\PoPAPI\API\Module::class] = [
            // Do not expose global fields
            \PoPAPI\API\Environment::SKIP_EXPOSING_GLOBAL_FIELDS_IN_FULL_SCHEMA => true,
        ];

        // If doing ?behavior=unrestricted, always enable certain features
        // Retrieve this service from the SystemContainer
        $systemInstanceManager = SystemInstanceManagerFacade::getInstance();
        /** @var EndpointHelpers */
        $endpointHelpers = $systemInstanceManager->getInstance(EndpointHelpers::class);
        if ($endpointHelpers->isRequestingAdminFixedSchemaGraphQLEndpoint()) {
            // Enable the "admin" fields
            $componentClassConfiguration[\PoP\ComponentModel\Module::class][ComponentModelEnvironment::ENABLE_ADMIN_SCHEMA] = true;
            // Enable the "self" fields
            $componentClassConfiguration[\GraphQLByPoP\GraphQLServer\Module::class][GraphQLServerEnvironment::EXPOSE_SELF_FIELD_IN_GRAPHQL_SCHEMA] = true;
            // Enable Nested mutations
            $componentClassConfiguration[\GraphQLByPoP\GraphQLServer\Module::class][GraphQLServerEnvironment::ENABLE_NESTED_MUTATIONS] = true;
            // Do not disable redundant mutation fields in the root type
            $componentClassConfiguration[\PoP\Engine\Module::class][EngineEnvironment::DISABLE_REDUNDANT_ROOT_TYPE_MUTATION_FIELDS] = false;
            // Allow disabling introspection via Access Control on field "__schema"
            $componentClassConfiguration[\GraphQLByPoP\GraphQLServer\Module::class][GraphQLServerEnvironment::EXPOSE_SCHEMA_INTROSPECTION_FIELD_IN_SCHEMA] = true;
            // Allow access to all entries for Root.option
            $componentClassConfiguration[\PoPCMSSchema\Settings\Module::class][SettingsEnvironment::SETTINGS_ENTRIES] = [];
            $componentClassConfiguration[\PoPCMSSchema\Settings\Module::class][SettingsEnvironment::SETTINGS_BEHAVIOR] = Behaviors::DENYLIST;
            // Allow access to all meta values
            $componentClassConfiguration[\PoPCMSSchema\CustomPostMeta\Module::class][CustomPostMetaEnvironment::CUSTOMPOST_META_ENTRIES] = [];
            $componentClassConfiguration[\PoPCMSSchema\CustomPostMeta\Module::class][CustomPostMetaEnvironment::CUSTOMPOST_META_BEHAVIOR] = Behaviors::DENYLIST;
            $componentClassConfiguration[\PoPCMSSchema\UserMeta\Module::class][UserMetaEnvironment::USER_META_ENTRIES] = [];
            $componentClassConfiguration[\PoPCMSSchema\UserMeta\Module::class][UserMetaEnvironment::USER_META_BEHAVIOR] = Behaviors::DENYLIST;
            $componentClassConfiguration[\PoPCMSSchema\CommentMeta\Module::class][CommentMetaEnvironment::COMMENT_META_ENTRIES] = [];
            $componentClassConfiguration[\PoPCMSSchema\CommentMeta\Module::class][CommentMetaEnvironment::COMMENT_META_BEHAVIOR] = Behaviors::DENYLIST;
            $componentClassConfiguration[\PoPCMSSchema\TaxonomyMeta\Module::class][TaxonomyMetaEnvironment::TAXONOMY_META_ENTRIES] = [];
            $componentClassConfiguration[\PoPCMSSchema\TaxonomyMeta\Module::class][TaxonomyMetaEnvironment::TAXONOMY_META_BEHAVIOR] = Behaviors::DENYLIST;
        }
        return $componentClassConfiguration;
    }

    /**
     * Return the opposite value
     */
    protected function opposite(bool $value): bool
    {
        return !$value;
    }

    protected function getModuleToComponentClassConfigurationMapping(): array
    {
        return [
            [
                'module' => EndpointFunctionalityModuleResolver::SINGLE_ENDPOINT,
                'class' => \GraphQLByPoP\GraphQLEndpointForWP\Module::class,
                'envVariable' => \GraphQLByPoP\GraphQLEndpointForWP\Environment::DISABLE_GRAPHQL_API_ENDPOINT,
                'callback' => $this->opposite(...),
            ],
            [
                'module' => ClientFunctionalityModuleResolver::GRAPHIQL_FOR_SINGLE_ENDPOINT,
                'class' => \GraphQLByPoP\GraphQLClientsForWP\Module::class,
                'envVariable' => \GraphQLByPoP\GraphQLClientsForWP\Environment::DISABLE_GRAPHIQL_CLIENT_ENDPOINT,
                'callback' => $this->opposite(...),
            ],
            [
                'module' => ClientFunctionalityModuleResolver::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT,
                'class' => \GraphQLByPoP\GraphQLClientsForWP\Module::class,
                'envVariable' => \GraphQLByPoP\GraphQLClientsForWP\Environment::DISABLE_VOYAGER_CLIENT_ENDPOINT,
                'callback' => $this->opposite(...),
            ],
            [
                'module' => ClientFunctionalityModuleResolver::GRAPHIQL_EXPLORER,
                'class' => \GraphQLByPoP\GraphQLClientsForWP\Module::class,
                'envVariable' => \GraphQLByPoP\GraphQLClientsForWP\Environment::USE_GRAPHIQL_EXPLORER,
            ],
        ];
    }

    /**
     * Provide the list of modules to check if they are enabled and,
     * if they are not, what component classes must skip initialization
     *
     * @return array<string,string[]>
     */
    protected function getModuleComponentClassesToSkipIfDisabled(): array
    {
        return [
            SchemaTypeModuleResolver::SCHEMA_CUSTOMPOSTS => [
                \PoPCMSSchema\CustomPosts\Module::class,
                \PoPCMSSchema\CustomPostsWP\Module::class,
                \PoPCMSSchema\CustomPostMedia\Module::class,
                \PoPWPSchema\CustomPosts\Module::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_GENERIC_CUSTOMPOSTS => [
                \PoPCMSSchema\GenericCustomPosts\Module::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_POSTS => [
                \PoPCMSSchema\Posts\Module::class,
                \PoPWPSchema\Posts\Module::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_COMMENTS => [
                \PoPCMSSchema\Comments\Module::class,
                \PoPWPSchema\Comments\Module::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_USERS => [
                \PoPCMSSchema\Users\Module::class,
                \PoPCMSSchema\UserState\Module::class,
                \PoPWPSchema\Users\Module::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_USER_ROLES => [
                \PoPCMSSchema\UserRoles\Module::class,
                \PoPCMSSchema\UserRolesWP\Module::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_USER_AVATARS => [
                \PoPCMSSchema\UserAvatars\Module::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_PAGES => [
                \PoPCMSSchema\Pages\Module::class,
                \PoPWPSchema\Pages\Module::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_MEDIA => [
                \PoPCMSSchema\CustomPostMedia\Module::class,
                \PoPCMSSchema\Media\Module::class,
                \PoPWPSchema\Media\Module::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_TAGS => [
                \PoPCMSSchema\Tags\Module::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_POST_TAGS => [
                \PoPCMSSchema\PostTags\Module::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_CATEGORIES => [
                \PoPCMSSchema\Categories\Module::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_POST_CATEGORIES => [
                \PoPCMSSchema\PostCategories\Module::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_MENUS => [
                \PoPCMSSchema\Menus\Module::class,
                \PoPWPSchema\Menus\Module::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_SETTINGS => [
                \PoPCMSSchema\Settings\Module::class,
            ],
            MutationSchemaTypeModuleResolver::SCHEMA_USER_STATE_MUTATIONS => [
                \PoPCMSSchema\UserStateMutations\Module::class,
            ],
            MutationSchemaTypeModuleResolver::SCHEMA_POST_MUTATIONS => [
                \PoPCMSSchema\PostMutations\Module::class,
            ],
            MutationSchemaTypeModuleResolver::SCHEMA_CUSTOMPOSTMEDIA_MUTATIONS => [
                \PoPCMSSchema\CustomPostMediaMutations\Module::class,
            ],
            MutationSchemaTypeModuleResolver::SCHEMA_POSTMEDIA_MUTATIONS => [
                \PoPCMSSchema\PostMediaMutations\Module::class,
            ],
            MutationSchemaTypeModuleResolver::SCHEMA_POST_TAG_MUTATIONS => [
                \PoPCMSSchema\PostTagMutations\Module::class,
            ],
            MutationSchemaTypeModuleResolver::SCHEMA_POST_CATEGORY_MUTATIONS => [
                \PoPCMSSchema\PostCategoryMutations\Module::class,
            ],
            MutationSchemaTypeModuleResolver::SCHEMA_COMMENT_MUTATIONS => [
                \PoPCMSSchema\CommentMutations\Module::class,
            ],
            MetaSchemaTypeModuleResolver::SCHEMA_CUSTOMPOST_META => [
                \PoPCMSSchema\CustomPostMeta\Module::class,
                \PoPWPSchema\CustomPostMeta\Module::class,
            ],
            MetaSchemaTypeModuleResolver::SCHEMA_USER_META => [
                \PoPCMSSchema\UserMeta\Module::class,
                \PoPWPSchema\UserMeta\Module::class,
            ],
            MetaSchemaTypeModuleResolver::SCHEMA_COMMENT_META => [
                \PoPCMSSchema\CommentMeta\Module::class,
                \PoPWPSchema\CommentMeta\Module::class,
            ],
            MetaSchemaTypeModuleResolver::SCHEMA_TAXONOMY_META => [
                \PoPCMSSchema\TaxonomyMeta\Module::class,
                \PoPWPSchema\TaxonomyMeta\Module::class,
            ],
        ];
    }
}
