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
use GraphQLAPI\GraphQLAPI\PluginManagement\MainPluginManager;
use GraphQLAPI\GraphQLAPI\PluginManagement\PluginConfigurationHelper;
use GraphQLAPI\GraphQLAPI\PluginSkeleton\AbstractMainPluginConfiguration;
use GraphQLAPI\GraphQLAPI\Services\Helpers\EndpointHelpers;
use GraphQLByPoP\GraphQLClientsForWP\Component as GraphQLClientsForWPComponent;
use GraphQLByPoP\GraphQLClientsForWP\ComponentConfiguration as GraphQLClientsForWPComponentConfiguration;
use GraphQLByPoP\GraphQLClientsForWP\Environment as GraphQLClientsForWPEnvironment;
use GraphQLByPoP\GraphQLEndpointForWP\Component as GraphQLEndpointForWPComponent;
use GraphQLByPoP\GraphQLEndpointForWP\ComponentConfiguration as GraphQLEndpointForWPComponentConfiguration;
use GraphQLByPoP\GraphQLEndpointForWP\Environment as GraphQLEndpointForWPEnvironment;
use GraphQLByPoP\GraphQLServer\Component as GraphQLServerComponent;
use GraphQLByPoP\GraphQLServer\ComponentConfiguration as GraphQLServerComponentConfiguration;
use GraphQLByPoP\GraphQLServer\Configuration\MutationSchemes;
use GraphQLByPoP\GraphQLServer\Environment as GraphQLServerEnvironment;
use PoP\AccessControl\Component as AccessControlComponent;
use PoP\AccessControl\ComponentConfiguration as AccessControlComponentConfiguration;
use PoP\AccessControl\Environment as AccessControlEnvironment;
use PoP\AccessControl\Schema\SchemaModes;
use PoP\CacheControl\Component as CacheControlComponent;
use PoP\CacheControl\ComponentConfiguration as CacheControlComponentConfiguration;
use PoP\CacheControl\Environment as CacheControlEnvironment;
use PoP\ComponentModel\Component as ComponentModelComponent;
use PoP\ComponentModel\ComponentConfiguration as ComponentModelComponentConfiguration;
use PoP\ComponentModel\Environment as ComponentModelEnvironment;
use PoP\Root\Facades\Instances\SystemInstanceManagerFacade;
use PoP\Engine\Component as EngineComponent;
use PoP\Engine\ComponentConfiguration as EngineComponentConfiguration;
use PoP\Engine\Environment as EngineEnvironment;
use PoPSchema\Categories\Component as CategoriesComponent;
use PoPSchema\Categories\ComponentConfiguration as CategoriesComponentConfiguration;
use PoPSchema\Categories\Environment as CategoriesEnvironment;
use PoPSchema\CommentMeta\Component as CommentMetaComponent;
use PoPSchema\CommentMeta\ComponentConfiguration as CommentMetaComponentConfiguration;
use PoPSchema\CommentMeta\Environment as CommentMetaEnvironment;
use PoPSchema\Comments\Component as CommentsComponent;
use PoPSchema\Comments\ComponentConfiguration as CommentsComponentConfiguration;
use PoPSchema\Comments\Environment as CommentsEnvironment;
use PoPSchema\CustomPostMeta\Component as CustomPostMetaComponent;
use PoPSchema\CustomPostMeta\ComponentConfiguration as CustomPostMetaComponentConfiguration;
use PoPSchema\CustomPostMeta\Environment as CustomPostMetaEnvironment;
use PoPSchema\CustomPosts\Component as CustomPostsComponent;
use PoPSchema\CustomPosts\ComponentConfiguration as CustomPostsComponentConfiguration;
use PoPSchema\CustomPosts\Environment as CustomPostsEnvironment;
use PoPSchema\GenericCustomPosts\Component as GenericCustomPostsComponent;
use PoPSchema\GenericCustomPosts\ComponentConfiguration as GenericCustomPostsComponentConfiguration;
use PoPSchema\GenericCustomPosts\Environment as GenericCustomPostsEnvironment;
use PoPSchema\Media\Component as MediaComponent;
use PoPSchema\Media\ComponentConfiguration as MediaComponentConfiguration;
use PoPSchema\Media\Environment as MediaEnvironment;
use PoPSchema\Menus\Component as MenusComponent;
use PoPSchema\Menus\ComponentConfiguration as MenusComponentConfiguration;
use PoPSchema\Menus\Environment as MenusEnvironment;
use PoPSchema\Pages\Component as PagesComponent;
use PoPSchema\Pages\ComponentConfiguration as PagesComponentConfiguration;
use PoPSchema\Pages\Environment as PagesEnvironment;
use PoPSchema\Posts\Component as PostsComponent;
use PoPSchema\Posts\ComponentConfiguration as PostsComponentConfiguration;
use PoPSchema\Posts\Environment as PostsEnvironment;
use PoPSchema\SchemaCommons\Constants\Behaviors;
use PoPSchema\Settings\Component as SettingsComponent;
use PoPSchema\Settings\ComponentConfiguration as SettingsComponentConfiguration;
use PoPSchema\Settings\Environment as SettingsEnvironment;
use PoPSchema\Tags\Component as TagsComponent;
use PoPSchema\Tags\ComponentConfiguration as TagsComponentConfiguration;
use PoPSchema\Tags\Environment as TagsEnvironment;
use PoPSchema\TaxonomyMeta\Component as TaxonomyMetaComponent;
use PoPSchema\TaxonomyMeta\ComponentConfiguration as TaxonomyMetaComponentConfiguration;
use PoPSchema\TaxonomyMeta\Environment as TaxonomyMetaEnvironment;
use PoPSchema\UserRoles\Component as UserRolesComponent;
use PoPSchema\UserRoles\ComponentConfiguration as UserRolesComponentConfiguration;
use PoPSchema\UserRoles\Environment as UserRolesEnvironment;
use PoPSchema\UserMeta\Component as UserMetaComponent;
use PoPSchema\UserMeta\ComponentConfiguration as UserMetaComponentConfiguration;
use PoPSchema\UserAvatars\ComponentConfiguration as UserAvatarsComponentConfiguration;
use PoPSchema\UserAvatars\Environment as UserAvatarsEnvironment;
use PoPSchema\UserMeta\Environment as UserMetaEnvironment;
use PoPSchema\Users\Component as UsersComponent;
use PoPSchema\Users\ComponentConfiguration as UsersComponentConfiguration;
use PoPSchema\Users\Environment as UsersEnvironment;

/**
 * Sets the configuration in all the PoP components from the main plugin.
 */
class PluginConfiguration extends AbstractMainPluginConfiguration
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
        return [
            // GraphQL single endpoint slug
            [
                'class' => GraphQLEndpointForWPComponentConfiguration::class,
                'envVariable' => GraphQLEndpointForWPEnvironment::GRAPHQL_API_ENDPOINT,
                'module' => EndpointFunctionalityModuleResolver::SINGLE_ENDPOINT,
                'option' => ModuleSettingOptions::PATH,
                'callback' => fn ($value) => PluginConfigurationHelper::getURLPathSettingValue(
                    $value,
                    EndpointFunctionalityModuleResolver::SINGLE_ENDPOINT,
                    ModuleSettingOptions::PATH
                ),
                'condition' => 'any',
            ],
            // Custom Endpoint path
            [
                'class' => ComponentConfiguration::class,
                'envVariable' => Environment::ENDPOINT_SLUG_BASE,
                'module' => EndpointFunctionalityModuleResolver::CUSTOM_ENDPOINTS,
                'option' => ModuleSettingOptions::PATH,
                'callback' => fn ($value) => PluginConfigurationHelper::getCPTPermalinkBasePathSettingValue(
                    $value,
                    EndpointFunctionalityModuleResolver::CUSTOM_ENDPOINTS,
                    ModuleSettingOptions::PATH
                ),
                'condition' => 'any',
            ],
            // Persisted Query path
            [
                'class' => ComponentConfiguration::class,
                'envVariable' => Environment::PERSISTED_QUERY_SLUG_BASE,
                'module' => EndpointFunctionalityModuleResolver::PERSISTED_QUERIES,
                'option' => ModuleSettingOptions::PATH,
                'callback' => fn ($value) => PluginConfigurationHelper::getCPTPermalinkBasePathSettingValue(
                    $value,
                    EndpointFunctionalityModuleResolver::PERSISTED_QUERIES,
                    ModuleSettingOptions::PATH
                ),
                'condition' => 'any',
            ],
            // GraphiQL client slug
            [
                'class' => GraphQLClientsForWPComponentConfiguration::class,
                'envVariable' => GraphQLClientsForWPEnvironment::GRAPHIQL_CLIENT_ENDPOINT,
                'module' => ClientFunctionalityModuleResolver::GRAPHIQL_FOR_SINGLE_ENDPOINT,
                'option' => ModuleSettingOptions::PATH,
                'callback' => fn ($value) => PluginConfigurationHelper::getURLPathSettingValue(
                    $value,
                    ClientFunctionalityModuleResolver::GRAPHIQL_FOR_SINGLE_ENDPOINT,
                    ModuleSettingOptions::PATH
                ),
                'condition' => 'any',
            ],
            // Voyager client slug
            [
                'class' => GraphQLClientsForWPComponentConfiguration::class,
                'envVariable' => GraphQLClientsForWPEnvironment::VOYAGER_CLIENT_ENDPOINT,
                'module' => ClientFunctionalityModuleResolver::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT,
                'option' => ModuleSettingOptions::PATH,
                'callback' => fn ($value) => PluginConfigurationHelper::getURLPathSettingValue(
                    $value,
                    ClientFunctionalityModuleResolver::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT,
                    ModuleSettingOptions::PATH
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
                'option' => $isRequestingGraphQLEndpointForAdminClientOrConfiguration ? ModuleSettingOptions::VALUE_FOR_ADMIN_CLIENTS : ModuleSettingOptions::DEFAULT_VALUE,
            ],
            // Enable nested mutations?
            // Only assign for Admin clients. For configuration it is assigned always, via the Fixed endpoint
            [
                'class' => GraphQLServerComponentConfiguration::class,
                'envVariable' => GraphQLServerEnvironment::ENABLE_NESTED_MUTATIONS,
                'module' => SchemaConfigurationFunctionalityModuleResolver::NESTED_MUTATIONS,
                'option' => $isRequestingGraphQLEndpointForAdminClientOnly ? ModuleSettingOptions::VALUE_FOR_ADMIN_CLIENTS : ModuleSettingOptions::DEFAULT_VALUE,
                'callback' => fn ($value) => $moduleRegistry->isModuleEnabled(SchemaConfigurationFunctionalityModuleResolver::NESTED_MUTATIONS) && $value != MutationSchemes::STANDARD,
            ],
            // Disable redundant mutation fields in the root type?
            [
                'class' => EngineComponentConfiguration::class,
                'envVariable' => EngineEnvironment::DISABLE_REDUNDANT_ROOT_TYPE_MUTATION_FIELDS,
                'module' => SchemaConfigurationFunctionalityModuleResolver::NESTED_MUTATIONS,
                'option' => $isRequestingGraphQLEndpointForAdminClientOnly ? ModuleSettingOptions::VALUE_FOR_ADMIN_CLIENTS : ModuleSettingOptions::DEFAULT_VALUE,
                'callback' => fn ($value) => $moduleRegistry->isModuleEnabled(SchemaConfigurationFunctionalityModuleResolver::NESTED_MUTATIONS) && $value == MutationSchemes::NESTED_WITHOUT_REDUNDANT_ROOT_FIELDS,
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
                'option' => ModuleSettingOptions::LIST_DEFAULT_LIMIT,
            ],
            [
                'class' => GenericCustomPostsComponentConfiguration::class,
                'envVariable' => GenericCustomPostsEnvironment::GENERIC_CUSTOMPOST_LIST_MAX_LIMIT,
                'module' => SchemaTypeModuleResolver::SCHEMA_GENERIC_CUSTOMPOSTS,
                'optionModule' => SchemaTypeModuleResolver::SCHEMA_CUSTOMPOSTS,
                'option' => ModuleSettingOptions::LIST_MAX_LIMIT,
            ],
            [
                'class' => GenericCustomPostsComponentConfiguration::class,
                'envVariable' => GenericCustomPostsEnvironment::GENERIC_CUSTOMPOST_TYPES,
                'module' => SchemaTypeModuleResolver::SCHEMA_GENERIC_CUSTOMPOSTS,
                'option' => ModuleSettingOptions::CUSTOMPOST_TYPES,
            ],
            // Post default/max limits, add to CustomPostUnion
            [
                'class' => PostsComponentConfiguration::class,
                'envVariable' => PostsEnvironment::POST_LIST_DEFAULT_LIMIT,
                'module' => SchemaTypeModuleResolver::SCHEMA_POSTS,
                'optionModule' => SchemaTypeModuleResolver::SCHEMA_POSTS,
                'option' => ModuleSettingOptions::LIST_DEFAULT_LIMIT,
            ],
            [
                'class' => PostsComponentConfiguration::class,
                'envVariable' => PostsEnvironment::POST_LIST_MAX_LIMIT,
                'module' => SchemaTypeModuleResolver::SCHEMA_POSTS,
                'optionModule' => SchemaTypeModuleResolver::SCHEMA_POSTS,
                'option' => ModuleSettingOptions::LIST_MAX_LIMIT,
            ],
            [
                'class' => PostsComponentConfiguration::class,
                'envVariable' => PostsEnvironment::ADD_POST_TYPE_TO_CUSTOMPOST_UNION_TYPES,
                'module' => SchemaTypeModuleResolver::SCHEMA_POSTS,
                'option' => ModuleSettingOptions::ADD_TYPE_TO_CUSTOMPOST_UNION_TYPE,
            ],
            // User default/max limits
            [
                'class' => UsersComponentConfiguration::class,
                'envVariable' => UsersEnvironment::USER_LIST_DEFAULT_LIMIT,
                'module' => SchemaTypeModuleResolver::SCHEMA_USERS,
                'option' => ModuleSettingOptions::LIST_DEFAULT_LIMIT,
            ],
            [
                'class' => UsersComponentConfiguration::class,
                'envVariable' => UsersEnvironment::USER_LIST_MAX_LIMIT,
                'module' => SchemaTypeModuleResolver::SCHEMA_USERS,
                'option' => ModuleSettingOptions::LIST_MAX_LIMIT,
            ],
            [
                'class' => UsersComponentConfiguration::class,
                'envVariable' => UsersEnvironment::TREAT_USER_EMAIL_AS_ADMIN_DATA,
                'module' => SchemaTypeModuleResolver::SCHEMA_USERS,
                'option' => SchemaTypeModuleResolver::OPTION_TREAT_USER_EMAIL_AS_ADMIN_DATA,
            ],
            // Comment default/max limits
            [
                'class' => CommentsComponentConfiguration::class,
                'envVariable' => CommentsEnvironment::ROOT_COMMENT_LIST_DEFAULT_LIMIT,
                'module' => SchemaTypeModuleResolver::SCHEMA_COMMENTS,
                'option' => SchemaTypeModuleResolver::OPTION_ROOT_COMMENT_LIST_DEFAULT_LIMIT,
            ],
            [
                'class' => CommentsComponentConfiguration::class,
                'envVariable' => CommentsEnvironment::CUSTOMPOST_COMMENT_OR_COMMENT_RESPONSE_LIST_DEFAULT_LIMIT,
                'module' => SchemaTypeModuleResolver::SCHEMA_COMMENTS,
                'option' => SchemaTypeModuleResolver::OPTION_CUSTOMPOST_COMMENT_OR_COMMENT_RESPONSE_LIST_DEFAULT_LIMIT,
            ],
            [
                'class' => CommentsComponentConfiguration::class,
                'envVariable' => CommentsEnvironment::COMMENT_LIST_MAX_LIMIT,
                'module' => SchemaTypeModuleResolver::SCHEMA_COMMENTS,
                'option' => ModuleSettingOptions::LIST_MAX_LIMIT,
            ],
            [
                'class' => CommentsComponentConfiguration::class,
                'envVariable' => CommentsEnvironment::TREAT_COMMENT_STATUS_AS_ADMIN_DATA,
                'module' => SchemaTypeModuleResolver::SCHEMA_COMMENTS,
                'option' => SchemaTypeModuleResolver::OPTION_TREAT_COMMENT_STATUS_AS_ADMIN_DATA,
            ],
            // Media default/max limits
            [
                'class' => MediaComponentConfiguration::class,
                'envVariable' => MediaEnvironment::MEDIA_LIST_DEFAULT_LIMIT,
                'module' => SchemaTypeModuleResolver::SCHEMA_MEDIA,
                'option' => ModuleSettingOptions::LIST_DEFAULT_LIMIT,
            ],
            [
                'class' => MediaComponentConfiguration::class,
                'envVariable' => MediaEnvironment::MEDIA_LIST_MAX_LIMIT,
                'module' => SchemaTypeModuleResolver::SCHEMA_MEDIA,
                'option' => ModuleSettingOptions::LIST_MAX_LIMIT,
            ],
            // Menu default/max limits
            [
                'class' => MenusComponentConfiguration::class,
                'envVariable' => MenusEnvironment::MENU_LIST_DEFAULT_LIMIT,
                'module' => SchemaTypeModuleResolver::SCHEMA_MENUS,
                'optionModule' => SchemaTypeModuleResolver::SCHEMA_MENUS,
                'option' => ModuleSettingOptions::LIST_DEFAULT_LIMIT,
            ],
            [
                'class' => MenusComponentConfiguration::class,
                'envVariable' => MenusEnvironment::MENU_LIST_MAX_LIMIT,
                'module' => SchemaTypeModuleResolver::SCHEMA_MENUS,
                'optionModule' => SchemaTypeModuleResolver::SCHEMA_MENUS,
                'option' => ModuleSettingOptions::LIST_MAX_LIMIT,
            ],
            // Tag default/max limits
            [
                'class' => TagsComponentConfiguration::class,
                'envVariable' => TagsEnvironment::TAG_LIST_DEFAULT_LIMIT,
                'module' => SchemaTypeModuleResolver::SCHEMA_TAGS,
                'option' => ModuleSettingOptions::LIST_DEFAULT_LIMIT,
            ],
            [
                'class' => TagsComponentConfiguration::class,
                'envVariable' => TagsEnvironment::TAG_LIST_MAX_LIMIT,
                'module' => SchemaTypeModuleResolver::SCHEMA_TAGS,
                'option' => ModuleSettingOptions::LIST_MAX_LIMIT,
            ],
            // Categories default/max limits
            [
                'class' => CategoriesComponentConfiguration::class,
                'envVariable' => CategoriesEnvironment::CATEGORY_LIST_DEFAULT_LIMIT,
                'module' => SchemaTypeModuleResolver::SCHEMA_CATEGORIES,
                'option' => ModuleSettingOptions::LIST_DEFAULT_LIMIT,
            ],
            [
                'class' => CategoriesComponentConfiguration::class,
                'envVariable' => CategoriesEnvironment::CATEGORY_LIST_MAX_LIMIT,
                'module' => SchemaTypeModuleResolver::SCHEMA_CATEGORIES,
                'option' => ModuleSettingOptions::LIST_MAX_LIMIT,
            ],
            // Page default/max limits, add to CustomPostUnion
            [
                'class' => PagesComponentConfiguration::class,
                'envVariable' => PagesEnvironment::PAGE_LIST_DEFAULT_LIMIT,
                'module' => SchemaTypeModuleResolver::SCHEMA_PAGES,
                'optionModule' => SchemaTypeModuleResolver::SCHEMA_PAGES,
                'option' => ModuleSettingOptions::LIST_DEFAULT_LIMIT,
            ],
            [
                'class' => PagesComponentConfiguration::class,
                'envVariable' => PagesEnvironment::PAGE_LIST_MAX_LIMIT,
                'module' => SchemaTypeModuleResolver::SCHEMA_PAGES,
                'optionModule' => SchemaTypeModuleResolver::SCHEMA_PAGES,
                'option' => ModuleSettingOptions::LIST_MAX_LIMIT,
            ],
            [
                'class' => PagesComponentConfiguration::class,
                'envVariable' => PagesEnvironment::ADD_PAGE_TYPE_TO_CUSTOMPOST_UNION_TYPES,
                'module' => SchemaTypeModuleResolver::SCHEMA_PAGES,
                'option' => ModuleSettingOptions::ADD_TYPE_TO_CUSTOMPOST_UNION_TYPE,
            ],
            // Custom post default/max limits
            [
                'class' => CustomPostsComponentConfiguration::class,
                'envVariable' => CustomPostsEnvironment::CUSTOMPOST_LIST_DEFAULT_LIMIT,
                'module' => SchemaTypeModuleResolver::SCHEMA_CUSTOMPOSTS,
                'option' => ModuleSettingOptions::LIST_DEFAULT_LIMIT,
            ],
            [
                'class' => CustomPostsComponentConfiguration::class,
                'envVariable' => CustomPostsEnvironment::CUSTOMPOST_LIST_MAX_LIMIT,
                'module' => SchemaTypeModuleResolver::SCHEMA_CUSTOMPOSTS,
                'option' => ModuleSettingOptions::LIST_MAX_LIMIT,
            ],
            [
                'class' => CustomPostsComponentConfiguration::class,
                'envVariable' => CustomPostsEnvironment::TREAT_CUSTOMPOST_STATUS_AS_ADMIN_DATA,
                'module' => SchemaTypeModuleResolver::SCHEMA_CUSTOMPOSTS,
                'option' => SchemaTypeModuleResolver::OPTION_TREAT_CUSTOMPOST_STATUS_AS_ADMIN_DATA,
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
                'option' => ModuleSettingOptions::ENTRIES,
                // Remove whitespaces, and empty entries (they mess up with regex)
                'callback' => fn (array $value) => array_filter(array_map('trim', $value)),
            ],
            [
                'class' => SettingsComponentConfiguration::class,
                'envVariable' => SettingsEnvironment::SETTINGS_BEHAVIOR,
                'module' => SchemaTypeModuleResolver::SCHEMA_SETTINGS,
                'option' => ModuleSettingOptions::BEHAVIOR,
            ],
            // Enable the "admin" schema: if doing ?behavior=unrestricted, it will already
            // be set by configuration. Otherwise, it uses this mapping
            [
                'class' => ComponentModelComponentConfiguration::class,
                'envVariable' => ComponentModelEnvironment::ENABLE_ADMIN_SCHEMA,
                'module' => SchemaTypeModuleResolver::SCHEMA_EXPOSE_ADMIN_DATA,
                'option' => $isRequestingGraphQLEndpointForAdminClientOnly ? ModuleSettingOptions::VALUE_FOR_ADMIN_CLIENTS : ModuleSettingOptions::DEFAULT_VALUE,
            ],
            // Add "self" fields to the schema?
            [
                'class' => GraphQLServerComponentConfiguration::class,
                'envVariable' => GraphQLServerEnvironment::EXPOSE_SELF_FIELD_IN_GRAPHQL_SCHEMA,
                'module' => SchemaTypeModuleResolver::SCHEMA_SELF_FIELDS,
                'option' => $isRequestingGraphQLEndpointForAdminClientOnly ? ModuleSettingOptions::VALUE_FOR_ADMIN_CLIENTS : ModuleSettingOptions::DEFAULT_VALUE,
            ],
            // White/Blacklisted entries to CustomPost.meta
            [
                'class' => CustomPostMetaComponentConfiguration::class,
                'envVariable' => CustomPostMetaEnvironment::CUSTOMPOST_META_ENTRIES,
                'module' => MetaSchemaTypeModuleResolver::SCHEMA_CUSTOMPOST_META,
                'option' => ModuleSettingOptions::ENTRIES,
                // Remove whitespaces, and empty entries (they mess up with regex)
                'callback' => fn (array $value) => array_filter(array_map('trim', $value)),
            ],
            [
                'class' => CustomPostMetaComponentConfiguration::class,
                'envVariable' => CustomPostMetaEnvironment::CUSTOMPOST_META_BEHAVIOR,
                'module' => MetaSchemaTypeModuleResolver::SCHEMA_CUSTOMPOST_META,
                'option' => ModuleSettingOptions::BEHAVIOR,
            ],
            // White/Blacklisted entries to User.meta
            [
                'class' => UserMetaComponentConfiguration::class,
                'envVariable' => UserMetaEnvironment::USER_META_ENTRIES,
                'module' => MetaSchemaTypeModuleResolver::SCHEMA_USER_META,
                'option' => ModuleSettingOptions::ENTRIES,
                // Remove whitespaces, and empty entries (they mess up with regex)
                'callback' => fn (array $value) => array_filter(array_map('trim', $value)),
            ],
            [
                'class' => UserMetaComponentConfiguration::class,
                'envVariable' => UserMetaEnvironment::USER_META_BEHAVIOR,
                'module' => MetaSchemaTypeModuleResolver::SCHEMA_USER_META,
                'option' => ModuleSettingOptions::BEHAVIOR,
            ],
            // White/Blacklisted entries to Comment.meta
            [
                'class' => CommentMetaComponentConfiguration::class,
                'envVariable' => CommentMetaEnvironment::COMMENT_META_ENTRIES,
                'module' => MetaSchemaTypeModuleResolver::SCHEMA_COMMENT_META,
                'option' => ModuleSettingOptions::ENTRIES,
                // Remove whitespaces, and empty entries (they mess up with regex)
                'callback' => fn (array $value) => array_filter(array_map('trim', $value)),
            ],
            [
                'class' => CommentMetaComponentConfiguration::class,
                'envVariable' => CommentMetaEnvironment::COMMENT_META_BEHAVIOR,
                'module' => MetaSchemaTypeModuleResolver::SCHEMA_COMMENT_META,
                'option' => ModuleSettingOptions::BEHAVIOR,
            ],
            // White/Blacklisted entries to PostTag.meta and PostCategory.meta
            [
                'class' => TaxonomyMetaComponentConfiguration::class,
                'envVariable' => TaxonomyMetaEnvironment::TAXONOMY_META_ENTRIES,
                'module' => MetaSchemaTypeModuleResolver::SCHEMA_TAXONOMY_META,
                'option' => ModuleSettingOptions::ENTRIES,
                // Remove whitespaces, and empty entries (they mess up with regex)
                'callback' => fn (array $value) => array_filter(array_map('trim', $value)),
            ],
            [
                'class' => TaxonomyMetaComponentConfiguration::class,
                'envVariable' => TaxonomyMetaEnvironment::TAXONOMY_META_BEHAVIOR,
                'module' => MetaSchemaTypeModuleResolver::SCHEMA_TAXONOMY_META,
                'option' => ModuleSettingOptions::BEHAVIOR,
            ],
            [
                'class' => UserAvatarsComponentConfiguration::class,
                'envVariable' => UserAvatarsEnvironment::USER_AVATAR_DEFAULT_SIZE,
                'module' => SchemaTypeModuleResolver::SCHEMA_USER_AVATARS,
                'option' => SchemaTypeModuleResolver::OPTION_DEFAULT_AVATAR_SIZE,
            ],
            [
                'class' => UserRolesComponentConfiguration::class,
                'envVariable' => UserRolesEnvironment::TREAT_USER_ROLE_AS_ADMIN_DATA,
                'module' => SchemaTypeModuleResolver::SCHEMA_USER_ROLES,
                'option' => SchemaTypeModuleResolver::OPTION_TREAT_USER_ROLE_AS_ADMIN_DATA,
            ],
            [
                'class' => UserRolesComponentConfiguration::class,
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
                'class' => \PoPSchema\CommentMutations\ComponentConfiguration::class,
                'envVariable' => \PoPSchema\CommentMutations\Environment::MUST_USER_BE_LOGGED_IN_TO_ADD_COMMENT,
                'callback' => fn () => \get_option('comment_registration') === '1',
            ],
            [
                'class' => \PoPSchema\CommentMutations\ComponentConfiguration::class,
                'envVariable' => \PoPSchema\CommentMutations\Environment::REQUIRE_COMMENTER_NAME_AND_EMAIL,
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
    }

    /**
     * Get the fixed configuration for all components required in the plugin
     *
     * @return array<string, array> [key]: Component class, [value]: Configuration
     */
    protected function getPredefinedComponentClassConfiguration(): array
    {
        $moduleRegistry = SystemModuleRegistryFacade::getInstance();
        $mainPluginURL = (string) MainPluginManager::getConfig('url');

        $componentClassConfiguration = [];
        $componentClassConfiguration[\PoP\ComponentModel\Component::class] = [
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
             * Enable passing a single value where a list is expected:
             * `{ posts(ids: 1) }` means `{ posts(ids: [1]) }`
             */
            ComponentModelEnvironment::CONVERT_INPUT_VALUE_FROM_SINGLE_TO_LIST => true,
            /**
             * Do not expose the `DangerouslyDynamic` scalar type
             */
            ComponentModelEnvironment::SKIP_EXPOSING_DANGEROUSLY_DYNAMIC_SCALAR_TYPE_IN_SCHEMA => true,
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
        ];
        $componentClassConfiguration[\GraphQLByPoP\GraphQLServer\Component::class] = [
            // Expose the "self" field when doing Low Level Query Editing
            GraphQLServerEnvironment::EXPOSE_SELF_FIELD_FOR_ROOT_TYPE_IN_GRAPHQL_SCHEMA => $moduleRegistry->isModuleEnabled(UserInterfaceFunctionalityModuleResolver::LOW_LEVEL_PERSISTED_QUERY_EDITING),
            // Do not send proactive deprecations
            GraphQLServerEnvironment::ENABLE_PROACTIVE_FEEDBACK_DEPRECATIONS => false,
        ];
        $componentClassConfiguration[\PoP\API\Component::class] = [
            // Do not expose global fields
            \PoP\API\Environment::SKIP_EXPOSING_GLOBAL_FIELDS_IN_FULL_SCHEMA => true,
            // Enable Mutations?
            \PoP\API\Environment::ENABLE_MUTATIONS => $moduleRegistry->isModuleEnabled(MutationSchemaTypeModuleResolver::SCHEMA_MUTATIONS),
        ];

        // If doing ?behavior=unrestricted, always enable certain features
        // Retrieve this service from the SystemContainer
        $systemInstanceManager = SystemInstanceManagerFacade::getInstance();
        /** @var EndpointHelpers */
        $endpointHelpers = $systemInstanceManager->getInstance(EndpointHelpers::class);
        if ($endpointHelpers->isRequestingAdminFixedSchemaGraphQLEndpoint()) {
            // Enable the "admin" fields
            $componentClassConfiguration[\PoP\ComponentModel\Component::class][ComponentModelEnvironment::ENABLE_ADMIN_SCHEMA] = true;
            // Enable the "self" fields
            $componentClassConfiguration[\GraphQLByPoP\GraphQLServer\Component::class][GraphQLServerEnvironment::EXPOSE_SELF_FIELD_IN_GRAPHQL_SCHEMA] = true;
            // Enable Nested mutations
            $componentClassConfiguration[\GraphQLByPoP\GraphQLServer\Component::class][GraphQLServerEnvironment::ENABLE_NESTED_MUTATIONS] = true;
            // Do not disable redundant mutation fields in the root type
            $componentClassConfiguration[\PoP\Engine\Component::class][EngineEnvironment::DISABLE_REDUNDANT_ROOT_TYPE_MUTATION_FIELDS] = false;
            // Allow disabling introspection via Access Control on field "__schema"
            $componentClassConfiguration[\GraphQLByPoP\GraphQLServer\Component::class][GraphQLServerEnvironment::EXPOSE_SCHEMA_INTROSPECTION_FIELD_IN_SCHEMA] = true;
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
                'class' => \GraphQLByPoP\GraphQLEndpointForWP\Component::class,
                'envVariable' => \GraphQLByPoP\GraphQLEndpointForWP\Environment::DISABLE_GRAPHQL_API_ENDPOINT,
                'callback' => [$this, 'opposite'],
            ],
            [
                'module' => ClientFunctionalityModuleResolver::GRAPHIQL_FOR_SINGLE_ENDPOINT,
                'class' => \GraphQLByPoP\GraphQLClientsForWP\Component::class,
                'envVariable' => \GraphQLByPoP\GraphQLClientsForWP\Environment::DISABLE_GRAPHIQL_CLIENT_ENDPOINT,
                'callback' => [$this, 'opposite'],
            ],
            [
                'module' => ClientFunctionalityModuleResolver::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT,
                'class' => \GraphQLByPoP\GraphQLClientsForWP\Component::class,
                'envVariable' => \GraphQLByPoP\GraphQLClientsForWP\Environment::DISABLE_VOYAGER_CLIENT_ENDPOINT,
                'callback' => [$this, 'opposite'],
            ],
            [
                'module' => ClientFunctionalityModuleResolver::GRAPHIQL_EXPLORER,
                'class' => \GraphQLByPoP\GraphQLClientsForWP\Component::class,
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
                \PoPSchema\CustomPosts\Component::class,
                \PoPSchema\CustomPostsWP\Component::class,
                \PoPSchema\CustomPostMedia\Component::class,
                \PoPWPSchema\CustomPosts\Component::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_GENERIC_CUSTOMPOSTS => [
                \PoPSchema\GenericCustomPosts\Component::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_POSTS => [
                \PoPSchema\Posts\Component::class,
                \PoPWPSchema\Posts\Component::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_COMMENTS => [
                \PoPSchema\Comments\Component::class,
                \PoPWPSchema\Comments\Component::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_USERS => [
                \PoPSchema\Users\Component::class,
                \PoPSchema\UserState\Component::class,
                \PoPWPSchema\Users\Component::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_USER_ROLES => [
                \PoPSchema\UserRoles\Component::class,
                \PoPSchema\UserRolesWP\Component::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_USER_AVATARS => [
                \PoPSchema\UserAvatars\Component::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_PAGES => [
                \PoPSchema\Pages\Component::class,
                \PoPWPSchema\Pages\Component::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_MEDIA => [
                \PoPSchema\CustomPostMedia\Component::class,
                \PoPSchema\Media\Component::class,
                \PoPWPSchema\Media\Component::class,
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
            SchemaTypeModuleResolver::SCHEMA_MENUS => [
                \PoPSchema\Menus\Component::class,
                \PoPWPSchema\Menus\Component::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_SETTINGS => [
                \PoPSchema\Settings\Component::class,
            ],
            MutationSchemaTypeModuleResolver::SCHEMA_USER_STATE_MUTATIONS => [
                \PoPSchema\UserStateMutations\Component::class,
            ],
            MutationSchemaTypeModuleResolver::SCHEMA_POST_MUTATIONS => [
                \PoPSchema\PostMutations\Component::class,
            ],
            MutationSchemaTypeModuleResolver::SCHEMA_CUSTOMPOSTMEDIA_MUTATIONS => [
                \PoPSchema\CustomPostMediaMutations\Component::class,
            ],
            MutationSchemaTypeModuleResolver::SCHEMA_POSTMEDIA_MUTATIONS => [
                \PoPSchema\PostMediaMutations\Component::class,
            ],
            MutationSchemaTypeModuleResolver::SCHEMA_POST_TAG_MUTATIONS => [
                \PoPSchema\PostTagMutations\Component::class,
            ],
            MutationSchemaTypeModuleResolver::SCHEMA_POST_CATEGORY_MUTATIONS => [
                \PoPSchema\PostCategoryMutations\Component::class,
            ],
            MutationSchemaTypeModuleResolver::SCHEMA_COMMENT_MUTATIONS => [
                \PoPSchema\CommentMutations\Component::class,
            ],
            MetaSchemaTypeModuleResolver::SCHEMA_CUSTOMPOST_META => [
                \PoPSchema\CustomPostMeta\Component::class,
                \PoPWPSchema\CustomPostMeta\Component::class,
            ],
            MetaSchemaTypeModuleResolver::SCHEMA_USER_META => [
                \PoPSchema\UserMeta\Component::class,
                \PoPWPSchema\UserMeta\Component::class,
            ],
            MetaSchemaTypeModuleResolver::SCHEMA_COMMENT_META => [
                \PoPSchema\CommentMeta\Component::class,
                \PoPWPSchema\CommentMeta\Component::class,
            ],
            MetaSchemaTypeModuleResolver::SCHEMA_TAXONOMY_META => [
                \PoPSchema\TaxonomyMeta\Component::class,
                \PoPWPSchema\TaxonomyMeta\Component::class,
            ],
        ];
    }
}
