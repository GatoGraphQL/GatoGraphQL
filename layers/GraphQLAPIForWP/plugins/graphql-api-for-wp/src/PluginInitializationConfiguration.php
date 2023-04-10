<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI;

use GraphQLAPI\GraphQLAPI\Constants\EndpointConfigurationGroups;
use GraphQLAPI\GraphQLAPI\Constants\ModuleSettingOptions;
use GraphQLAPI\GraphQLAPI\Facades\Registries\SystemModuleRegistryFacade;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ClientFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\MetaSchemaTypeModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\MutationSchemaTypeModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\PluginGeneralSettingsFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaTypeModuleResolver;
use GraphQLAPI\GraphQLAPI\PluginManagement\PluginOptionsFormHandler;
use GraphQLAPI\GraphQLAPI\PluginSkeleton\AbstractMainPluginInitializationConfiguration;
use GraphQLAPI\GraphQLAPI\Services\Helpers\EndpointHelpers;
use GraphQLByPoP\GraphQLClientsForWP\Environment as GraphQLClientsForWPEnvironment;
use GraphQLByPoP\GraphQLClientsForWP\Module as GraphQLClientsForWPModule;
use GraphQLByPoP\GraphQLEndpointForWP\Environment as GraphQLEndpointForWPEnvironment;
use GraphQLByPoP\GraphQLEndpointForWP\Module as GraphQLEndpointForWPModule;
use GraphQLByPoP\GraphQLServer\Configuration\MutationSchemes;
use GraphQLByPoP\GraphQLServer\Environment as GraphQLServerEnvironment;
use GraphQLByPoP\GraphQLServer\Module as GraphQLServerModule;
use PoPCMSSchema\Categories\Environment as CategoriesEnvironment;
use PoPCMSSchema\Categories\Module as CategoriesModule;
use PoPCMSSchema\CommentMeta\Environment as CommentMetaEnvironment;
use PoPCMSSchema\CommentMeta\Module as CommentMetaModule;
use PoPCMSSchema\Comments\Environment as CommentsEnvironment;
use PoPCMSSchema\Comments\Module as CommentsModule;
use PoPCMSSchema\CustomPostMeta\Environment as CustomPostMetaEnvironment;
use PoPCMSSchema\CustomPostMeta\Module as CustomPostMetaModule;
use PoPCMSSchema\CustomPosts\Environment as CustomPostsEnvironment;
use PoPCMSSchema\CustomPosts\Module as CustomPostsModule;
use PoPCMSSchema\Media\Environment as MediaEnvironment;
use PoPCMSSchema\Media\Module as MediaModule;
use PoPCMSSchema\Menus\Environment as MenusEnvironment;
use PoPCMSSchema\Menus\Module as MenusModule;
use PoPCMSSchema\Pages\Environment as PagesEnvironment;
use PoPCMSSchema\Pages\Module as PagesModule;
use PoPCMSSchema\Posts\Environment as PostsEnvironment;
use PoPCMSSchema\Posts\Module as PostsModule;
use PoPCMSSchema\Settings\Environment as SettingsEnvironment;
use PoPCMSSchema\Settings\Module as SettingsModule;
use PoPCMSSchema\Tags\Environment as TagsEnvironment;
use PoPCMSSchema\Tags\Module as TagsModule;
use PoPCMSSchema\TaxonomyMeta\Environment as TaxonomyMetaEnvironment;
use PoPCMSSchema\TaxonomyMeta\Module as TaxonomyMetaModule;
use PoPCMSSchema\UserAvatars\Environment as UserAvatarsEnvironment;
use PoPCMSSchema\UserAvatars\Module as UserAvatarsModule;
use PoPCMSSchema\UserMeta\Environment as UserMetaEnvironment;
use PoPCMSSchema\UserMeta\Module as UserMetaModule;
use PoPCMSSchema\UserRoles\Environment as UserRolesEnvironment;
use PoPCMSSchema\UserRoles\Module as UserRolesModule;
use PoPCMSSchema\Users\Environment as UsersEnvironment;
use PoPCMSSchema\Users\Module as UsersModule;
use PoPSchema\SchemaCommons\Constants\Behaviors;
use PoP\ComponentModel\Environment as ComponentModelEnvironment;
use PoP\ComponentModel\Module as ComponentModelModule;
use PoP\Engine\Environment as EngineEnvironment;
use PoP\Engine\Module as EngineModule;
use PoP\Root\Environment as RootEnvironment;
use PoP\Root\Facades\Instances\SystemInstanceManagerFacade;
use PoP\Root\Module\ModuleInterface;

/**
 * Sets the configuration in all the PoP components from the main plugin.
 */
class PluginInitializationConfiguration extends AbstractMainPluginInitializationConfiguration
{
    protected function isContainerCachingEnabled(): bool
    {
        return PluginEnvironment::isContainerCachingEnabled();
    }

    /**
     * Define the values for certain environment constants from the plugin settings
     *
     * @return array<mixed[]>
     */
    protected function getEnvironmentConstantsFromSettingsMapping(): array
    {
        $moduleRegistry = SystemModuleRegistryFacade::getInstance();
        $systemInstanceManager = SystemInstanceManagerFacade::getInstance();
        /** @var EndpointHelpers */
        $endpointHelpers = $systemInstanceManager->getInstance(EndpointHelpers::class);
        // Get the possible states of wp-admin clients requesting the endpoint:
        // 1. Only GraphiQL and Voyager clients
        $isRequestingDefaultAdminGraphQLEndpoint = $endpointHelpers->isRequestingDefaultAdminGraphQLEndpoint();
        // 2. GraphiQL and Voyager clients + ACL/CCL configurations
        $isRequestingNonPersistedQueryAdminGraphQLEndpoint = $endpointHelpers->isRequestingNonPersistedQueryAdminGraphQLEndpoint();
        $pluginOptionsFormHandler = new PluginOptionsFormHandler();
        return [
            // Client IP Server's Property Name
            [
                'class' => ComponentModelModule::class,
                'envVariable' => ComponentModelEnvironment::CLIENT_IP_ADDRESS_SERVER_PROPERTY_NAME,
                'module' => PluginGeneralSettingsFunctionalityModuleResolver::SERVER_IP_CONFIGURATION,
                'option' => PluginGeneralSettingsFunctionalityModuleResolver::OPTION_CLIENT_IP_ADDRESS_SERVER_PROPERTY_NAME,
            ],
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
            // Use namespacing?
            [
                'class' => ComponentModelModule::class,
                'envVariable' => ComponentModelEnvironment::NAMESPACE_TYPES_AND_INTERFACES,
                'module' => SchemaConfigurationFunctionalityModuleResolver::SCHEMA_NAMESPACING,
                'option' => $isRequestingNonPersistedQueryAdminGraphQLEndpoint ? ModuleSettingOptions::VALUE_FOR_ADMIN_CLIENTS : ModuleSettingOptions::DEFAULT_VALUE,
            ],
            // Expose "self" fields in the schema?
            [
                'class' => ComponentModelModule::class,
                'envVariable' => ComponentModelEnvironment::ENABLE_SELF_FIELD,
                'module' => SchemaConfigurationFunctionalityModuleResolver::SCHEMA_SELF_FIELDS,
                'option' => $isRequestingDefaultAdminGraphQLEndpoint ? ModuleSettingOptions::VALUE_FOR_ADMIN_CLIENTS : ModuleSettingOptions::DEFAULT_VALUE,
            ],
            // Enable nested mutations?
            // Only assign for Admin clients. For configuration it is assigned always, via the Fixed endpoint
            [
                'class' => GraphQLServerModule::class,
                'envVariable' => GraphQLServerEnvironment::ENABLE_NESTED_MUTATIONS,
                'module' => SchemaConfigurationFunctionalityModuleResolver::NESTED_MUTATIONS,
                'option' => $isRequestingDefaultAdminGraphQLEndpoint ? ModuleSettingOptions::VALUE_FOR_ADMIN_CLIENTS : ModuleSettingOptions::DEFAULT_VALUE,
                'callback' => fn ($value) => $moduleRegistry->isModuleEnabled(SchemaConfigurationFunctionalityModuleResolver::NESTED_MUTATIONS) && $value !== MutationSchemes::STANDARD,
            ],
            // Disable redundant mutation fields in the root type?
            [
                'class' => EngineModule::class,
                'envVariable' => EngineEnvironment::DISABLE_REDUNDANT_ROOT_TYPE_MUTATION_FIELDS,
                'module' => SchemaConfigurationFunctionalityModuleResolver::NESTED_MUTATIONS,
                'option' => $isRequestingDefaultAdminGraphQLEndpoint ? ModuleSettingOptions::VALUE_FOR_ADMIN_CLIENTS : ModuleSettingOptions::DEFAULT_VALUE,
                'callback' => fn ($value) => $moduleRegistry->isModuleEnabled(SchemaConfigurationFunctionalityModuleResolver::NESTED_MUTATIONS) && $value === MutationSchemes::NESTED_WITHOUT_REDUNDANT_ROOT_FIELDS,
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
                'envVariable' => UsersEnvironment::TREAT_USER_EMAIL_AS_SENSITIVE_DATA,
                'module' => SchemaTypeModuleResolver::SCHEMA_USERS,
                'option' => SchemaTypeModuleResolver::OPTION_TREAT_USER_EMAIL_AS_SENSITIVE_DATA,
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
                'envVariable' => CommentsEnvironment::TREAT_COMMENT_STATUS_AS_SENSITIVE_DATA,
                'module' => SchemaTypeModuleResolver::SCHEMA_COMMENTS,
                'option' => SchemaTypeModuleResolver::OPTION_TREAT_COMMENT_STATUS_AS_SENSITIVE_DATA,
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
            [
                'class' => TagsModule::class,
                'envVariable' => TagsEnvironment::QUERYABLE_TAG_TAXONOMIES,
                'module' => SchemaTypeModuleResolver::SCHEMA_TAGS,
                'option' => ModuleSettingOptions::TAG_TAXONOMIES,
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
            [
                'class' => CategoriesModule::class,
                'envVariable' => CategoriesEnvironment::QUERYABLE_CATEGORY_TAXONOMIES,
                'module' => SchemaTypeModuleResolver::SCHEMA_CATEGORIES,
                'option' => ModuleSettingOptions::CATEGORY_TAXONOMIES,
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
                'envVariable' => CustomPostsEnvironment::TREAT_CUSTOMPOST_STATUS_AS_SENSITIVE_DATA,
                'module' => SchemaTypeModuleResolver::SCHEMA_CUSTOMPOSTS,
                'option' => SchemaTypeModuleResolver::OPTION_TREAT_CUSTOMPOST_STATUS_AS_SENSITIVE_DATA,
            ],
            // Custom post, if there is only one custom type, use it instead of the Union
            [
                'class' => CustomPostsModule::class,
                'envVariable' => CustomPostsEnvironment::USE_SINGLE_TYPE_INSTEAD_OF_CUSTOMPOST_UNION_TYPE,
                'module' => SchemaTypeModuleResolver::SCHEMA_CUSTOMPOSTS,
                'option' => SchemaTypeModuleResolver::OPTION_USE_SINGLE_TYPE_INSTEAD_OF_UNION_TYPE,
            ],
            [
                'class' => CustomPostsModule::class,
                'envVariable' => CustomPostsEnvironment::QUERYABLE_CUSTOMPOST_TYPES,
                'module' => SchemaTypeModuleResolver::SCHEMA_CUSTOMPOSTS,
                'option' => ModuleSettingOptions::CUSTOMPOST_TYPES,
            ],
            // White/Blacklisted entries to Root.optionValue
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
            // Enable the “sensitive” data: in the admin endpoint it will already
            // be set by configuration. Otherwise, it uses this mapping
            [
                'class' => ComponentModelModule::class,
                'envVariable' => ComponentModelEnvironment::EXPOSE_SENSITIVE_DATA_IN_SCHEMA,
                'module' => SchemaConfigurationFunctionalityModuleResolver::SCHEMA_EXPOSE_SENSITIVE_DATA,
                'option' => $isRequestingDefaultAdminGraphQLEndpoint ? ModuleSettingOptions::VALUE_FOR_ADMIN_CLIENTS : ModuleSettingOptions::DEFAULT_VALUE,
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
                'class' => UserMetaModule::class,
                'envVariable' => UserMetaEnvironment::USER_META_ENTRIES,
                'module' => MetaSchemaTypeModuleResolver::SCHEMA_USER_META,
                'option' => ModuleSettingOptions::ENTRIES,
                // Remove whitespaces, and empty entries (they mess up with regex)
                'callback' => fn (array $value) => array_filter(array_map(trim(...), $value)),
            ],
            [
                'class' => UserMetaModule::class,
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
                'class' => UserAvatarsModule::class,
                'envVariable' => UserAvatarsEnvironment::USER_AVATAR_DEFAULT_SIZE,
                'module' => SchemaTypeModuleResolver::SCHEMA_USER_AVATARS,
                'option' => SchemaTypeModuleResolver::OPTION_DEFAULT_AVATAR_SIZE,
            ],
            [
                'class' => UserRolesModule::class,
                'envVariable' => UserRolesEnvironment::TREAT_USER_ROLE_AS_SENSITIVE_DATA,
                'module' => SchemaTypeModuleResolver::SCHEMA_USER_ROLES,
                'option' => SchemaTypeModuleResolver::OPTION_TREAT_USER_ROLE_AS_SENSITIVE_DATA,
            ],
            [
                'class' => UserRolesModule::class,
                'envVariable' => UserRolesEnvironment::TREAT_USER_CAPABILITY_AS_SENSITIVE_DATA,
                'module' => SchemaTypeModuleResolver::SCHEMA_USER_ROLES,
                'option' => SchemaTypeModuleResolver::OPTION_TREAT_USER_CAPABILITY_AS_SENSITIVE_DATA,
            ],
            // Do not use the Payloadable types for mutations
            [
                'class' => \PoPCMSSchema\CommentMutations\Module::class,
                'envVariable' => \PoPCMSSchema\CommentMutations\Environment::USE_PAYLOADABLE_COMMENT_MUTATIONS,
                'module' => MutationSchemaTypeModuleResolver::SCHEMA_MUTATIONS,
                'option' => $isRequestingNonPersistedQueryAdminGraphQLEndpoint ? MutationSchemaTypeModuleResolver::USE_PAYLOADABLE_MUTATIONS_VALUE_FOR_ADMIN_CLIENTS : MutationSchemaTypeModuleResolver::USE_PAYLOADABLE_MUTATIONS_DEFAULT_VALUE,
            ],
            [
                'class' => \PoPCMSSchema\CustomPostCategoryMutations\Module::class,
                'envVariable' => \PoPCMSSchema\CustomPostCategoryMutations\Environment::USE_PAYLOADABLE_CUSTOMPOSTCATEGORY_MUTATIONS,
                'module' => MutationSchemaTypeModuleResolver::SCHEMA_MUTATIONS,
                'option' => $isRequestingNonPersistedQueryAdminGraphQLEndpoint ? MutationSchemaTypeModuleResolver::USE_PAYLOADABLE_MUTATIONS_VALUE_FOR_ADMIN_CLIENTS : MutationSchemaTypeModuleResolver::USE_PAYLOADABLE_MUTATIONS_DEFAULT_VALUE,
            ],
            [
                'class' => \PoPCMSSchema\CustomPostMutations\Module::class,
                'envVariable' => \PoPCMSSchema\CustomPostMutations\Environment::USE_PAYLOADABLE_CUSTOMPOST_MUTATIONS,
                'module' => MutationSchemaTypeModuleResolver::SCHEMA_MUTATIONS,
                'option' => $isRequestingNonPersistedQueryAdminGraphQLEndpoint ? MutationSchemaTypeModuleResolver::USE_PAYLOADABLE_MUTATIONS_VALUE_FOR_ADMIN_CLIENTS : MutationSchemaTypeModuleResolver::USE_PAYLOADABLE_MUTATIONS_DEFAULT_VALUE,
            ],
            [
                'class' => \PoPCMSSchema\CustomPostTagMutations\Module::class,
                'envVariable' => \PoPCMSSchema\CustomPostTagMutations\Environment::USE_PAYLOADABLE_CUSTOMPOSTTAG_MUTATIONS,
                'module' => MutationSchemaTypeModuleResolver::SCHEMA_MUTATIONS,
                'option' => $isRequestingNonPersistedQueryAdminGraphQLEndpoint ? MutationSchemaTypeModuleResolver::USE_PAYLOADABLE_MUTATIONS_VALUE_FOR_ADMIN_CLIENTS : MutationSchemaTypeModuleResolver::USE_PAYLOADABLE_MUTATIONS_DEFAULT_VALUE,
            ],
            [
                'class' => \PoPCMSSchema\CustomPostMediaMutations\Module::class,
                'envVariable' => \PoPCMSSchema\CustomPostMediaMutations\Environment::USE_PAYLOADABLE_CUSTOMPOSTMEDIA_MUTATIONS,
                'module' => MutationSchemaTypeModuleResolver::SCHEMA_MUTATIONS,
                'option' => $isRequestingNonPersistedQueryAdminGraphQLEndpoint ? MutationSchemaTypeModuleResolver::USE_PAYLOADABLE_MUTATIONS_VALUE_FOR_ADMIN_CLIENTS : MutationSchemaTypeModuleResolver::USE_PAYLOADABLE_MUTATIONS_DEFAULT_VALUE,
            ],
            [
                'class' => \PoPCMSSchema\UserStateMutations\Module::class,
                'envVariable' => \PoPCMSSchema\UserStateMutations\Environment::USE_PAYLOADABLE_USERSTATE_MUTATIONS,
                'module' => MutationSchemaTypeModuleResolver::SCHEMA_MUTATIONS,
                'option' => $isRequestingNonPersistedQueryAdminGraphQLEndpoint ? MutationSchemaTypeModuleResolver::USE_PAYLOADABLE_MUTATIONS_VALUE_FOR_ADMIN_CLIENTS : MutationSchemaTypeModuleResolver::USE_PAYLOADABLE_MUTATIONS_DEFAULT_VALUE,
            ],
        ];
    }

    /**
     * Define the values for certain environment constants from the plugin settings
     * @return array<int,array{"class": class-string<ModuleInterface>, envVariable: string, callback: callable}>
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
     * @return array<int,array{class: class-string<ModuleInterface>, envVariable: string}>
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
                'class' => ComponentModelModule::class,
                'envVariable' => ComponentModelEnvironment::NAMESPACE_TYPES_AND_INTERFACES,
            ],
            [
                'class' => ComponentModelModule::class,
                'envVariable' => ComponentModelEnvironment::EXPOSE_SENSITIVE_DATA_IN_SCHEMA,
            ],
        ];
    }

    /**
     * Get the fixed configuration for all components required in the plugin
     *
     * @return array<class-string<ModuleInterface>,array<string,mixed>> [key]: Module class, [value]: Configuration
     */
    protected function getPredefinedModuleClassConfiguration(): array
    {
        $moduleRegistry = SystemModuleRegistryFacade::getInstance();
        $mainPluginURL = PluginApp::getMainPlugin()->getPluginURL();

        $moduleClassConfiguration = [];
        $moduleClassConfiguration[\PoP\Root\Module::class] = [
            /**
             * Can pass state for "variables" and "actions"
             */
            RootEnvironment::ENABLE_PASSING_STATE_VIA_REQUEST => true,
        ];
        $moduleClassConfiguration[ComponentModelModule::class] = [
            ComponentModelEnvironment::ENABLE_TYPENAME_GLOBAL_FIELDS => false,
            /**
             * Enable Mutations?
             */
            ComponentModelEnvironment::ENABLE_MUTATIONS => $moduleRegistry->isModuleEnabled(MutationSchemaTypeModuleResolver::SCHEMA_MUTATIONS),
        ];
        $moduleClassConfiguration[GraphQLClientsForWPModule::class] = [
            GraphQLClientsForWPEnvironment::GRAPHQL_CLIENTS_COMPONENT_URL => $mainPluginURL . 'vendor/graphql-by-pop/graphql-clients-for-wp',
        ];
        $moduleClassConfiguration[\PoPAPI\APIEndpointsForWP\Module::class] = [
            // Disable the Native endpoint
            \PoPAPI\APIEndpointsForWP\Environment::DISABLE_NATIVE_API_ENDPOINT => true,
        ];
        $moduleClassConfiguration[CustomPostsModule::class] = [
            // The default queryable custom post types are defined by this plugin
            CustomPostsEnvironment::DISABLE_PACKAGES_ADDING_DEFAULT_QUERYABLE_CUSTOMPOST_TYPES => true,
        ];

        return $moduleClassConfiguration;
    }

    /**
     * Get the fixed configuration for all components required in the plugin
     * when requesting some specific group in the admin endpoint
     *
     * @return array<class-string<ModuleInterface>,array<string,mixed>> [key]: Module class, [value]: Configuration
     */
    protected function doGetPredefinedAdminEndpointModuleClassConfiguration(string $endpointGroup): array
    {
        // Default (i.e. `null`) and all admin endpoints
        $moduleClassConfiguration = [
            ComponentModelModule::class => [
                // Enable the “sensitive” data
                ComponentModelEnvironment::EXPOSE_SENSITIVE_DATA_IN_SCHEMA => true,
                // Enable the "self" fields
                ComponentModelEnvironment::ENABLE_SELF_FIELD => true
            ],
            // Allow access to all entries for Root.optionValue
            SettingsModule::class => [
                SettingsEnvironment::SETTINGS_ENTRIES => [],
                SettingsEnvironment::SETTINGS_BEHAVIOR => Behaviors::DENY,
            ],
            // Allow access to all meta values
            CustomPostMetaModule::class => [
                CustomPostMetaEnvironment::CUSTOMPOST_META_ENTRIES => [],
                CustomPostMetaEnvironment::CUSTOMPOST_META_BEHAVIOR => Behaviors::DENY,
            ],
            UserMetaModule::class => [
                UserMetaEnvironment::USER_META_ENTRIES => [],
                UserMetaEnvironment::USER_META_BEHAVIOR => Behaviors::DENY,
            ],
            CommentMetaModule::class => [
                CommentMetaEnvironment::COMMENT_META_ENTRIES => [],
                CommentMetaEnvironment::COMMENT_META_BEHAVIOR => Behaviors::DENY,
            ],
            TaxonomyMetaModule::class => [
                TaxonomyMetaEnvironment::TAXONOMY_META_ENTRIES => [],
                TaxonomyMetaEnvironment::TAXONOMY_META_BEHAVIOR => Behaviors::DENY,
            ],
        ];
        if ($endpointGroup === EndpointConfigurationGroups::PLUGIN_INTERNAL_WP_EDITOR) {
            $moduleClassConfiguration = array_merge_recursive(
                $moduleClassConfiguration,
                [
                    GraphQLServerModule::class => [
                        // Enable Nested mutations
                        GraphQLServerEnvironment::ENABLE_NESTED_MUTATIONS => true,
                    ],
                    EngineModule::class => [
                        // Do not disable redundant mutation fields in the root type
                        EngineEnvironment::DISABLE_REDUNDANT_ROOT_TYPE_MUTATION_FIELDS => false,
                    ],
                    // Do not use the Payloadable types for mutations
                    \PoPCMSSchema\CommentMutations\Module::class => [
                        \PoPCMSSchema\CommentMutations\Environment::USE_PAYLOADABLE_COMMENT_MUTATIONS => false,
                    ],
                    \PoPCMSSchema\CustomPostCategoryMutations\Module::class => [
                        \PoPCMSSchema\CustomPostCategoryMutations\Environment::USE_PAYLOADABLE_CUSTOMPOSTCATEGORY_MUTATIONS => false,
                    ],
                    \PoPCMSSchema\CustomPostMutations\Module::class => [
                        \PoPCMSSchema\CustomPostMutations\Environment::USE_PAYLOADABLE_CUSTOMPOST_MUTATIONS => false,
                    ],
                    \PoPCMSSchema\CustomPostTagMutations\Module::class => [
                        \PoPCMSSchema\CustomPostTagMutations\Environment::USE_PAYLOADABLE_CUSTOMPOSTTAG_MUTATIONS => false,
                    ],
                    \PoPCMSSchema\CustomPostMediaMutations\Module::class => [
                        \PoPCMSSchema\CustomPostMediaMutations\Environment::USE_PAYLOADABLE_CUSTOMPOSTMEDIA_MUTATIONS => false,
                    ],
                    \PoPCMSSchema\UserStateMutations\Module::class => [
                        \PoPCMSSchema\UserStateMutations\Environment::USE_PAYLOADABLE_USERSTATE_MUTATIONS => false,
                    ],
                ]
            );
        }
        /** @var array<class-string<ModuleInterface>,array<string,mixed>> */
        return $moduleClassConfiguration;
    }

    /**
     * Return the opposite value
     */
    protected function opposite(bool $value): bool
    {
        return !$value;
    }

    /**
     * @return array<mixed[]>
     */
    protected function getModuleToModuleClassConfigurationMapping(): array
    {
        return [
            [
                'module' => EndpointFunctionalityModuleResolver::SINGLE_ENDPOINT,
                'class' => GraphQLEndpointForWPModule::class,
                'envVariable' => GraphQLEndpointForWPEnvironment::DISABLE_GRAPHQL_API_ENDPOINT,
                'callback' => $this->opposite(...),
            ],
            [
                'module' => ClientFunctionalityModuleResolver::GRAPHIQL_FOR_SINGLE_ENDPOINT,
                'class' => GraphQLClientsForWPModule::class,
                'envVariable' => GraphQLClientsForWPEnvironment::DISABLE_GRAPHIQL_CLIENT_ENDPOINT,
                'callback' => $this->opposite(...),
            ],
            [
                'module' => ClientFunctionalityModuleResolver::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT,
                'class' => GraphQLClientsForWPModule::class,
                'envVariable' => GraphQLClientsForWPEnvironment::DISABLE_VOYAGER_CLIENT_ENDPOINT,
                'callback' => $this->opposite(...),
            ],
            [
                'module' => ClientFunctionalityModuleResolver::GRAPHIQL_EXPLORER,
                'class' => GraphQLClientsForWPModule::class,
                'envVariable' => GraphQLClientsForWPEnvironment::USE_GRAPHIQL_EXPLORER,
            ],
        ];
    }

    /**
     * Provide the list of modules to check if they are enabled and,
     * if they are not, what component classes must skip initialization
     *
     * @return array<string,array<class-string<ModuleInterface>>>
     */
    protected function getModuleClassesToSkipIfModuleDisabled(): array
    {
        return [
            SchemaTypeModuleResolver::SCHEMA_CUSTOMPOSTS => [
                CustomPostsModule::class,
                \PoPCMSSchema\CustomPostsWP\Module::class,
                \PoPCMSSchema\CustomPostMedia\Module::class,
                \PoPCMSSchema\CustomPostMediaWP\Module::class,
                \PoPWPSchema\CustomPosts\Module::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_POSTS => [
                PostsModule::class,
                \PoPCMSSchema\PostsWP\Module::class,
                \PoPWPSchema\Posts\Module::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_COMMENTS => [
                CommentsModule::class,
                \PoPCMSSchema\CommentsWP\Module::class,
                \PoPWPSchema\Comments\Module::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_USERS => [
                UsersModule::class,
                \PoPCMSSchema\UsersWP\Module::class,
                \PoPCMSSchema\UserState\Module::class,
                \PoPCMSSchema\UserStateWP\Module::class,
                \PoPWPSchema\Users\Module::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_USER_ROLES => [
                UserRolesModule::class,
                \PoPCMSSchema\UserRolesWP\Module::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_USER_AVATARS => [
                UserAvatarsModule::class,
                \PoPCMSSchema\UserAvatarsWP\Module::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_PAGES => [
                PagesModule::class,
                \PoPCMSSchema\PagesWP\Module::class,
                \PoPWPSchema\Pages\Module::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_MEDIA => [
                \PoPCMSSchema\CustomPostMedia\Module::class,
                \PoPCMSSchema\CustomPostMediaWP\Module::class,
                MediaModule::class,
                \PoPWPSchema\Media\Module::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_TAGS => [
                TagsModule::class,
                \PoPCMSSchema\TagsWP\Module::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_POST_TAGS => [
                \PoPCMSSchema\PostTags\Module::class,
                \PoPCMSSchema\PostTagsWP\Module::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_CATEGORIES => [
                CategoriesModule::class,
                \PoPCMSSchema\CategoriesWP\Module::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_POST_CATEGORIES => [
                \PoPCMSSchema\PostCategories\Module::class,
                \PoPCMSSchema\PostCategoriesWP\Module::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_MENUS => [
                MenusModule::class,
                \PoPCMSSchema\MenusWP\Module::class,
                \PoPWPSchema\Menus\Module::class,
            ],
            SchemaTypeModuleResolver::SCHEMA_SETTINGS => [
                SettingsModule::class,
                \PoPCMSSchema\SettingsWP\Module::class,
            ],
            MutationSchemaTypeModuleResolver::SCHEMA_USER_STATE_MUTATIONS => [
                \PoPCMSSchema\UserStateMutations\Module::class,
                \PoPCMSSchema\UserStateMutationsWP\Module::class,
            ],
            MutationSchemaTypeModuleResolver::SCHEMA_PAGE_MUTATIONS => [
                \PoPCMSSchema\PageMutations\Module::class,
            ],
            MutationSchemaTypeModuleResolver::SCHEMA_POST_MUTATIONS => [
                \PoPCMSSchema\PostMutations\Module::class,
            ],
            MutationSchemaTypeModuleResolver::SCHEMA_CUSTOMPOSTMEDIA_MUTATIONS => [
                \PoPCMSSchema\CustomPostMediaMutations\Module::class,
                \PoPCMSSchema\CustomPostMediaMutationsWP\Module::class,
            ],
            MutationSchemaTypeModuleResolver::SCHEMA_PAGEMEDIA_MUTATIONS => [
                \PoPCMSSchema\PageMediaMutations\Module::class,
            ],
            MutationSchemaTypeModuleResolver::SCHEMA_POSTMEDIA_MUTATIONS => [
                \PoPCMSSchema\PostMediaMutations\Module::class,
            ],
            MutationSchemaTypeModuleResolver::SCHEMA_POST_TAG_MUTATIONS => [
                \PoPCMSSchema\PostTagMutations\Module::class,
                \PoPCMSSchema\PostTagMutationsWP\Module::class,
            ],
            MutationSchemaTypeModuleResolver::SCHEMA_POST_CATEGORY_MUTATIONS => [
                \PoPCMSSchema\PostCategoryMutations\Module::class,
                \PoPCMSSchema\PostCategoryMutationsWP\Module::class,
            ],
            MutationSchemaTypeModuleResolver::SCHEMA_COMMENT_MUTATIONS => [
                \PoPCMSSchema\CommentMutations\Module::class,
                \PoPCMSSchema\CommentMutationsWP\Module::class,
            ],
            MetaSchemaTypeModuleResolver::SCHEMA_CUSTOMPOST_META => [
                CustomPostMetaModule::class,
                \PoPCMSSchema\CustomPostMetaWP\Module::class,
                \PoPWPSchema\CustomPostMeta\Module::class,
            ],
            MetaSchemaTypeModuleResolver::SCHEMA_USER_META => [
                UserMetaModule::class,
                \PoPCMSSchema\UserMetaWP\Module::class,
                \PoPWPSchema\UserMeta\Module::class,
            ],
            MetaSchemaTypeModuleResolver::SCHEMA_COMMENT_META => [
                CommentMetaModule::class,
                \PoPCMSSchema\CommentMetaWP\Module::class,
                \PoPWPSchema\CommentMeta\Module::class,
            ],
            MetaSchemaTypeModuleResolver::SCHEMA_TAXONOMY_META => [
                TaxonomyMetaModule::class,
                \PoPCMSSchema\TaxonomyMetaWP\Module::class,
                \PoPWPSchema\TaxonomyMeta\Module::class,
            ],
        ];
    }
}
