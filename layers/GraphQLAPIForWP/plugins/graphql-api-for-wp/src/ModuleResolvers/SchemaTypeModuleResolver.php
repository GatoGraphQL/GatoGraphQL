<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\Plugin;
use PoPSchema\SchemaCommons\Constants\Behaviors;
use PoPSchema\Menus\TypeResolvers\MenuTypeResolver;
use PoPSchema\Pages\TypeResolvers\PageTypeResolver;
use PoPSchema\Posts\TypeResolvers\PostTypeResolver;
use PoPSchema\Users\TypeResolvers\UserTypeResolver;
use GraphQLAPI\GraphQLAPI\ModuleSettings\Properties;
use PoPSchema\Media\TypeResolvers\MediaTypeResolver;
use PoPSchema\Comments\TypeResolvers\CommentTypeResolver;
use PoPSchema\PostTags\TypeResolvers\PostTagTypeResolver;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use PoPSchema\UserRolesWP\TypeResolvers\UserRoleTypeResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ModuleResolverTrait;
use PoPSchema\CustomPosts\TypeResolvers\CustomPostUnionTypeResolver;
use PoPSchema\PostCategories\TypeResolvers\PostCategoryTypeResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\AbstractSchemaTypeModuleResolver;
use PoPSchema\GenericCustomPosts\TypeResolvers\GenericCustomPostTypeResolver;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLEndpointCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLPersistedQueryCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLCacheControlListCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLAccessControlListCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLSchemaConfigurationCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLFieldDeprecationListCustomPostType;

class SchemaTypeModuleResolver extends AbstractSchemaTypeModuleResolver
{
    use ModuleResolverTrait {
        ModuleResolverTrait::hasDocumentation as upstreamHasDocumentation;
    }

    public const SCHEMA_ADMIN_SCHEMA = Plugin::NAMESPACE . '\schema-admin-schema';
    public const SCHEMA_MUTATIONS = Plugin::NAMESPACE . '\schema-mutations';
    public const SCHEMA_CUSTOMPOSTS = Plugin::NAMESPACE . '\schema-customposts';
    public const SCHEMA_GENERIC_CUSTOMPOSTS = Plugin::NAMESPACE . '\schema-generic-customposts';
    public const SCHEMA_POSTS = Plugin::NAMESPACE . '\schema-posts';
    public const SCHEMA_COMMENTS = Plugin::NAMESPACE . '\schema-comments';
    public const SCHEMA_USERS = Plugin::NAMESPACE . '\schema-users';
    public const SCHEMA_USER_ROLES = Plugin::NAMESPACE . '\schema-user-roles';
    public const SCHEMA_PAGES = Plugin::NAMESPACE . '\schema-pages';
    public const SCHEMA_MEDIA = Plugin::NAMESPACE . '\schema-media';
    public const SCHEMA_TAGS = Plugin::NAMESPACE . '\schema-tags';
    public const SCHEMA_POST_TAGS = Plugin::NAMESPACE . '\schema-post-tags';
    public const SCHEMA_CATEGORIES = Plugin::NAMESPACE . '\schema-categories';
    public const SCHEMA_POST_CATEGORIES = Plugin::NAMESPACE . '\schema-post-categories';
    public const SCHEMA_CUSTOMPOST_META = Plugin::NAMESPACE . '\schema-custompost-meta';
    public const SCHEMA_USER_META = Plugin::NAMESPACE . '\schema-user-meta';
    public const SCHEMA_COMMENT_META = Plugin::NAMESPACE . '\schema-comment-meta';
    public const SCHEMA_TAXONOMY_META = Plugin::NAMESPACE . '\schema-taxonomy-meta';
    public const SCHEMA_MENUS = Plugin::NAMESPACE . '\schema-menus';
    public const SCHEMA_SETTINGS = Plugin::NAMESPACE . '\schema-settings';
    public const SCHEMA_USER_STATE_MUTATIONS = Plugin::NAMESPACE . '\schema-user-state-mutations';
    public const SCHEMA_CUSTOMPOST_MUTATIONS = Plugin::NAMESPACE . '\schema-custompost-mutations';
    public const SCHEMA_POST_MUTATIONS = Plugin::NAMESPACE . '\schema-post-mutations';
    public const SCHEMA_CUSTOMPOSTMEDIA_MUTATIONS = Plugin::NAMESPACE . '\schema-custompostmedia-mutations';
    public const SCHEMA_POST_TAG_MUTATIONS = Plugin::NAMESPACE . '\schema-post-tag-mutations';
    public const SCHEMA_POST_CATEGORY_MUTATIONS = Plugin::NAMESPACE . '\schema-post-category-mutations';
    public const SCHEMA_COMMENT_MUTATIONS = Plugin::NAMESPACE . '\schema-comment-mutations';

    /**
     * Setting options
     */
    public const OPTION_ENABLE_ADMIN_SCHEMA = 'enable-admin-schema';
    public const OPTION_LIST_DEFAULT_LIMIT = 'list-default-limit';
    public const OPTION_LIST_MAX_LIMIT = 'list-max-limit';
    public const OPTION_ADD_TYPE_TO_CUSTOMPOST_UNION_TYPE = 'add-type-to-custompost-union-type';
    public const OPTION_USE_SINGLE_TYPE_INSTEAD_OF_UNION_TYPE = 'use-single-type-instead-of-union-type';
    public const OPTION_CUSTOMPOST_TYPES = 'custompost-types';
    public const OPTION_ENTRIES = 'entries';
    public const OPTION_BEHAVIOR = 'behavior';

    /**
     * Hooks
     */
    public const HOOK_GENERIC_CUSTOMPOST_TYPES = __CLASS__ . ':generic-custompost-types';

    /**
     * Make all properties nullable, becase the ModuleRegistry is registered
     * in the SystemContainer, where there are no typeResolvers so it will be null,
     * and in the ApplicationContainer, from where the "Modules" page is resolved
     * and which does have all the typeResolvers.
     * Function `getDescription` will only be accessed from the Application Container,
     * so the properties will not be null in that situation.
     */
    public function __construct(
        ModuleRegistryInterface $moduleRegistry,
        protected ?CommentTypeResolver $commentTypeResolver,
        protected ?CustomPostUnionTypeResolver $customPostUnionTypeResolver,
        protected ?GenericCustomPostTypeResolver $genericCustomPostTypeResolver,
        protected ?MediaTypeResolver $mediaTypeResolver,
        protected ?PageTypeResolver $pageTypeResolver,
        protected ?PostTagTypeResolver $postTagTypeResolver,
        protected ?PostCategoryTypeResolver $postCategoryTypeResolver,
        protected ?MenuTypeResolver $menuTypeResolver,
        protected ?PostTypeResolver $postTypeResolver,
        protected ?UserRoleTypeResolver $userRoleTypeResolver,
        protected ?UserTypeResolver $userTypeResolver
    ) {
        parent::__construct($moduleRegistry);
    }

    /**
     * @return string[]
     */
    public function getModulesToResolve(): array
    {
        return [
            self::SCHEMA_ADMIN_SCHEMA,
            self::SCHEMA_MUTATIONS,
            self::SCHEMA_CUSTOMPOSTS,
            self::SCHEMA_GENERIC_CUSTOMPOSTS,
            self::SCHEMA_POSTS,
            self::SCHEMA_PAGES,
            self::SCHEMA_USERS,
            self::SCHEMA_USER_ROLES,
            self::SCHEMA_COMMENTS,
            self::SCHEMA_TAGS,
            self::SCHEMA_POST_TAGS,
            self::SCHEMA_CATEGORIES,
            self::SCHEMA_POST_CATEGORIES,
            self::SCHEMA_CUSTOMPOST_META,
            self::SCHEMA_USER_META,
            self::SCHEMA_COMMENT_META,
            self::SCHEMA_TAXONOMY_META,
            self::SCHEMA_MENUS,
            self::SCHEMA_SETTINGS,
            self::SCHEMA_MEDIA,
            self::SCHEMA_USER_STATE_MUTATIONS,
            self::SCHEMA_CUSTOMPOST_MUTATIONS,
            self::SCHEMA_POST_MUTATIONS,
            self::SCHEMA_CUSTOMPOSTMEDIA_MUTATIONS,
            self::SCHEMA_POST_TAG_MUTATIONS,
            self::SCHEMA_POST_CATEGORY_MUTATIONS,
            self::SCHEMA_COMMENT_MUTATIONS,
        ];
    }

    /**
     * The priority to display the modules from this resolver in the Modules page.
     * The higher the number, the earlier it shows
     */
    public function getPriority(): int
    {
        return 100;
    }

    /**
     * @return array<array> List of entries that must be satisfied, each entry is an array where at least 1 module must be satisfied
     */
    public function getDependedModuleLists(string $module): array
    {
        switch ($module) {
            case self::SCHEMA_USER_ROLES:
                return [
                    [
                        self::SCHEMA_USERS,
                    ],
                ];
            case self::SCHEMA_GENERIC_CUSTOMPOSTS:
            case self::SCHEMA_POSTS:
            case self::SCHEMA_PAGES:
            case self::SCHEMA_COMMENTS:
            case self::SCHEMA_TAGS:
            case self::SCHEMA_CATEGORIES:
                return [
                    [
                        self::SCHEMA_CUSTOMPOSTS,
                    ],
                ];
            case self::SCHEMA_POST_TAGS:
                return [
                    [
                        self::SCHEMA_POSTS,
                    ],
                    [
                        self::SCHEMA_TAGS,
                    ],
                ];
            case self::SCHEMA_POST_CATEGORIES:
                return [
                    [
                        self::SCHEMA_POSTS,
                    ],
                    [
                        self::SCHEMA_CATEGORIES,
                    ],
                ];
            case self::SCHEMA_CUSTOMPOST_META:
                return [
                    [
                        self::SCHEMA_CUSTOMPOSTS,
                    ],
                ];
            case self::SCHEMA_USER_META:
                return [
                    [
                        self::SCHEMA_USERS,
                    ],
                ];
            case self::SCHEMA_COMMENT_META:
                return [
                    [
                        self::SCHEMA_COMMENTS,
                    ],
                ];
            case self::SCHEMA_TAXONOMY_META:
                return [
                    [
                        self::SCHEMA_TAGS,
                        self::SCHEMA_CATEGORIES,
                    ],
                ];
            case self::SCHEMA_USER_STATE_MUTATIONS:
                return [
                    [
                        self::SCHEMA_MUTATIONS,
                    ],
                ];
            case self::SCHEMA_CUSTOMPOST_MUTATIONS:
                return [
                    [
                        self::SCHEMA_USER_STATE_MUTATIONS,
                    ],
                    [
                        self::SCHEMA_CUSTOMPOSTS,
                    ],
                ];
            case self::SCHEMA_POST_MUTATIONS:
                return [
                    [
                        self::SCHEMA_POSTS,
                    ],
                    [
                        self::SCHEMA_CUSTOMPOST_MUTATIONS,
                    ],
                ];
            case self::SCHEMA_CUSTOMPOSTMEDIA_MUTATIONS:
                return [
                    [
                        self::SCHEMA_MEDIA,
                    ],
                    [
                        self::SCHEMA_CUSTOMPOST_MUTATIONS,
                    ],
                ];
            case self::SCHEMA_POST_TAG_MUTATIONS:
                return [
                    [
                        self::SCHEMA_POST_TAGS,
                    ],
                    [
                        self::SCHEMA_POST_MUTATIONS,
                    ],
                ];
            case self::SCHEMA_POST_CATEGORY_MUTATIONS:
                return [
                    [
                        self::SCHEMA_POST_CATEGORIES,
                    ],
                    [
                        self::SCHEMA_POST_MUTATIONS,
                    ],
                ];
            case self::SCHEMA_COMMENT_MUTATIONS:
                return [
                    [
                        self::SCHEMA_USER_STATE_MUTATIONS,
                    ],
                    [
                        self::SCHEMA_COMMENTS,
                    ],
                ];
        }
        return parent::getDependedModuleLists($module);
    }

    public function getName(string $module): string
    {
        $names = [
            self::SCHEMA_ADMIN_SCHEMA => \__('Schema for the Admin', 'graphql-api'),
            self::SCHEMA_MUTATIONS => \__('Schema Mutations', 'graphql-api'),
            self::SCHEMA_GENERIC_CUSTOMPOSTS => \__('Schema Generic Custom Posts', 'graphql-api'),
            self::SCHEMA_POSTS => \__('Schema Posts', 'graphql-api'),
            self::SCHEMA_COMMENTS => \__('Schema Comments', 'graphql-api'),
            self::SCHEMA_USERS => \__('Schema Users', 'graphql-api'),
            self::SCHEMA_USER_ROLES => \__('Schema User Roles', 'graphql-api'),
            self::SCHEMA_PAGES => \__('Schema Pages', 'graphql-api'),
            self::SCHEMA_MEDIA => \__('Schema Media', 'graphql-api'),
            self::SCHEMA_TAGS => \__('Schema Tags', 'graphql-api'),
            self::SCHEMA_POST_TAGS => \__('Schema Post Tags', 'graphql-api'),
            self::SCHEMA_CATEGORIES => \__('Schema Categories', 'graphql-api'),
            self::SCHEMA_POST_CATEGORIES => \__('Schema Post Categories', 'graphql-api'),
            self::SCHEMA_CUSTOMPOST_META => \__('Schema Custom Post Meta', 'graphql-api'),
            self::SCHEMA_USER_META => \__('Schema User Meta', 'graphql-api'),
            self::SCHEMA_COMMENT_META => \__('Schema Comment Meta', 'graphql-api'),
            self::SCHEMA_TAXONOMY_META => \__('Schema Taxonomy Meta', 'graphql-api'),
            self::SCHEMA_MENUS => \__('Schema Menus', 'graphql-api'),
            self::SCHEMA_SETTINGS => \__('Schema Settings', 'graphql-api'),
            self::SCHEMA_CUSTOMPOSTS => \__('Schema Custom Posts', 'graphql-api'),
            self::SCHEMA_USER_STATE_MUTATIONS => \__('Schema User State Mutations', 'graphql-api'),
            self::SCHEMA_CUSTOMPOST_MUTATIONS => \__('Schema Custom Post Mutations', 'graphql-api'),
            self::SCHEMA_POST_MUTATIONS => \__('Schema Post Mutations', 'graphql-api'),
            self::SCHEMA_CUSTOMPOSTMEDIA_MUTATIONS => \__('Schema Custom Post Media Mutations', 'graphql-api'),
            self::SCHEMA_POST_TAG_MUTATIONS => \__('Schema Post Tag Mutations', 'graphql-api'),
            self::SCHEMA_POST_CATEGORY_MUTATIONS => \__('Schema Post Category Mutations', 'graphql-api'),
            self::SCHEMA_COMMENT_MUTATIONS => \__('Schema Comment Mutations', 'graphql-api'),
        ];
        return $names[$module] ?? $module;
    }

    public function getDescription(string $module): string
    {
        switch ($module) {
            case self::SCHEMA_ADMIN_SCHEMA:
                return \__('Add "unrestricted" admin fields to the schema', 'graphql-api');
            case self::SCHEMA_MUTATIONS:
                return \__('Modify data by executing mutations', 'graphql-api');
            case self::SCHEMA_GENERIC_CUSTOMPOSTS:
                return sprintf(
                    \__('Query any custom post type (added to the schema or not), through a generic type <code>%1$s</code>', 'graphql-api'),
                    $this->genericCustomPostTypeResolver->getTypeName()
                );
            case self::SCHEMA_POSTS:
                return sprintf(
                    \__('Query %1$s, through type <code>%2$s</code> added to the schema', 'graphql-api'),
                    \__('posts', 'graphql-api'),
                    $this->postTypeResolver->getTypeName()
                );
            case self::SCHEMA_USERS:
                return sprintf(
                    \__('Query %1$s, through type <code>%2$s</code> added to the schema', 'graphql-api'),
                    \__('users', 'graphql-api'),
                    $this->userTypeResolver->getTypeName()
                );
            case self::SCHEMA_USER_ROLES:
                return sprintf(
                    \__('Query %1$s, through type <code>%2$s</code> added to the schema', 'graphql-api'),
                    \__('user roles', 'graphql-api'),
                    $this->userRoleTypeResolver->getTypeName()
                );
            case self::SCHEMA_PAGES:
                return sprintf(
                    \__('Query %1$s, through type <code>%2$s</code> added to the schema', 'graphql-api'),
                    \__('pages', 'graphql-api'),
                    $this->pageTypeResolver->getTypeName()
                );
            case self::SCHEMA_MEDIA:
                return sprintf(
                    \__('Query %1$s, through type <code>%2$s</code> added to the schema', 'graphql-api'),
                    \__('media elements', 'graphql-api'),
                    $this->mediaTypeResolver->getTypeName()
                );
            case self::SCHEMA_COMMENTS:
                return sprintf(
                    \__('Query %1$s, through type <code>%2$s</code> added to the schema', 'graphql-api'),
                    \__('comments', 'graphql-api'),
                    $this->commentTypeResolver->getTypeName()
                );
            case self::SCHEMA_POST_TAGS:
                return sprintf(
                    \__('Query %1$s, through type <code>%2$s</code> added to the schema', 'graphql-api'),
                    \__('post tags', 'graphql-api'),
                    $this->postTagTypeResolver->getTypeName()
                );
            case self::SCHEMA_POST_CATEGORIES:
                return sprintf(
                    \__('Query %1$s, through type <code>%2$s</code> added to the schema', 'graphql-api'),
                    \__('post categories', 'graphql-api'),
                    $this->postCategoryTypeResolver->getTypeName()
                );
            case self::SCHEMA_CUSTOMPOST_META:
                return sprintf(
                    \__('Add the <code>%1$s</code> field to custom posts, such as type <code>%2$s</code>', 'graphql-api'),
                    'meta',
                    $this->postTypeResolver->getTypeName()
                );
            case self::SCHEMA_USER_META:
                return sprintf(
                    \__('Add the <code>%1$s</code> field to type <code>%2$s</code>', 'graphql-api'),
                    'meta',
                    $this->userTypeResolver->getTypeName()
                );
            case self::SCHEMA_COMMENT_META:
                return sprintf(
                    \__('Add the <code>%1$s</code> field to type <code>%2$s</code>', 'graphql-api'),
                    'meta',
                    $this->commentTypeResolver->getTypeName()
                );
            case self::SCHEMA_TAXONOMY_META:
                return sprintf(
                    \__('Add the <code>%1$s</code> field to taxonomies, such as types <code>%2$s</code> and <code>%3$s</code>', 'graphql-api'),
                    'meta',
                    $this->postTagTypeResolver->getTypeName(),
                    $this->postCategoryTypeResolver->getTypeName()
                );
            case self::SCHEMA_MENUS:
                return sprintf(
                    \__('Query %1$s, through type <code>%2$s</code> added to the schema', 'graphql-api'),
                    \__('menus', 'graphql-api'),
                    $this->menuTypeResolver->getTypeName()
                );
            case self::SCHEMA_SETTINGS:
                return \__('Fetch settings from the site', 'graphql-api');
            case self::SCHEMA_CUSTOMPOSTS:
                return \__('Base functionality for all custom posts', 'graphql-api');
            case self::SCHEMA_TAGS:
                return \__('Base functionality for all tags', 'graphql-api');
            case self::SCHEMA_CATEGORIES:
                return \__('Base functionality for all categories', 'graphql-api');
            case self::SCHEMA_USER_STATE_MUTATIONS:
                return \__('Have the user log-in, and be able to perform mutations', 'graphql-api');
            case self::SCHEMA_CUSTOMPOST_MUTATIONS:
                return \__('Base functionality to mutate custom posts', 'graphql-api');
            case self::SCHEMA_POST_MUTATIONS:
                return sprintf(
                    \__('Execute mutations on %1$s', 'graphql-api'),
                    \__('posts', 'graphql-api')
                );
            case self::SCHEMA_CUSTOMPOSTMEDIA_MUTATIONS:
                return \__('Execute mutations concerning media items on custom posts', 'graphql-api');
            case self::SCHEMA_POST_TAG_MUTATIONS:
                return \__('Add tags to posts', 'graphql-api');
            case self::SCHEMA_POST_CATEGORY_MUTATIONS:
                return \__('Add categories to posts', 'graphql-api');
            case self::SCHEMA_COMMENT_MUTATIONS:
                return \__('Create comments', 'graphql-api');
        }
        return parent::getDescription($module);
    }

    public function isEnabledByDefault(string $module): bool
    {
        switch ($module) {
            case self::SCHEMA_ADMIN_SCHEMA:
            case self::SCHEMA_CUSTOMPOST_META:
            case self::SCHEMA_USER_META:
            case self::SCHEMA_COMMENT_META:
            case self::SCHEMA_TAXONOMY_META:
                return false;
        }
        return parent::isEnabledByDefault($module);
    }

    /**
     * Does the module have HTML Documentation?
     */
    public function hasDocumentation(string $module): bool
    {
        switch ($module) {
            case self::SCHEMA_POSTS:
            case self::SCHEMA_PAGES:
            case self::SCHEMA_USERS:
            case self::SCHEMA_USER_ROLES:
            case self::SCHEMA_COMMENTS:
            case self::SCHEMA_TAGS:
            case self::SCHEMA_POST_TAGS:
            case self::SCHEMA_CATEGORIES:
            case self::SCHEMA_POST_CATEGORIES:
            case self::SCHEMA_MENUS:
            case self::SCHEMA_MEDIA:
            case self::SCHEMA_CUSTOMPOST_MUTATIONS:
            case self::SCHEMA_POST_MUTATIONS:
            case self::SCHEMA_CUSTOMPOSTMEDIA_MUTATIONS:
            case self::SCHEMA_POST_TAG_MUTATIONS:
            case self::SCHEMA_POST_CATEGORY_MUTATIONS:
            case self::SCHEMA_COMMENT_MUTATIONS:
                return false;
        }
        return $this->upstreamHasDocumentation($module);
    }

    /**
     * Indicate if the given value is valid for that option
     */
    public function isValidValue(string $module, string $option, mixed $value): bool
    {
        if (
            in_array(
                $module,
                [
                    self::SCHEMA_CUSTOMPOSTS,
                    // self::SCHEMA_GENERIC_CUSTOMPOSTS,
                    // self::SCHEMA_POSTS,
                    self::SCHEMA_USERS,
                    self::SCHEMA_TAGS,
                    self::SCHEMA_CATEGORIES,
                    // self::SCHEMA_PAGES,
                ]
            ) && in_array(
                $option,
                [
                    self::OPTION_LIST_DEFAULT_LIMIT,
                    self::OPTION_LIST_MAX_LIMIT,
                ]
            )
        ) {
            // It can't be less than -1, or 0
            if ($value < -1 or $value === 0) {
                return false;
            }
        }
        return parent::isValidValue($module, $option, $value);
    }

    /**
     * Default value for an option set by the module
     */
    public function getSettingsDefaultValue(string $module, string $option): mixed
    {
        $defaultMetaValues = [
            self::OPTION_ENTRIES => [],
            self::OPTION_BEHAVIOR => Behaviors::ALLOWLIST,
        ];
        $defaultValues = [
            self::SCHEMA_ADMIN_SCHEMA => [
                self::OPTION_ENABLE_ADMIN_SCHEMA => false,
            ],
            self::SCHEMA_CUSTOMPOSTS => [
                self::OPTION_LIST_DEFAULT_LIMIT => 10,
                self::OPTION_LIST_MAX_LIMIT => 100,
                self::OPTION_USE_SINGLE_TYPE_INSTEAD_OF_UNION_TYPE => false,
            ],
            self::SCHEMA_GENERIC_CUSTOMPOSTS => [
                // self::OPTION_LIST_DEFAULT_LIMIT => 10,
                // self::OPTION_LIST_MAX_LIMIT => 100,
                self::OPTION_CUSTOMPOST_TYPES => ['post'],
            ],
            self::SCHEMA_POSTS => [
                // self::OPTION_LIST_DEFAULT_LIMIT => 10,
                // self::OPTION_LIST_MAX_LIMIT => 100,
                self::OPTION_ADD_TYPE_TO_CUSTOMPOST_UNION_TYPE => true,
            ],
            self::SCHEMA_PAGES => [
                // self::OPTION_LIST_DEFAULT_LIMIT => 10,
                // self::OPTION_LIST_MAX_LIMIT => 100,
                self::OPTION_ADD_TYPE_TO_CUSTOMPOST_UNION_TYPE => false,
            ],
            self::SCHEMA_USERS => [
                self::OPTION_LIST_DEFAULT_LIMIT => 10,
                self::OPTION_LIST_MAX_LIMIT => 100,
            ],
            self::SCHEMA_TAGS => [
                self::OPTION_LIST_DEFAULT_LIMIT => 20,
                self::OPTION_LIST_MAX_LIMIT => 200,
            ],
            self::SCHEMA_CATEGORIES => [
                self::OPTION_LIST_DEFAULT_LIMIT => 20,
                self::OPTION_LIST_MAX_LIMIT => 200,
            ],
            self::SCHEMA_SETTINGS => [
                self::OPTION_ENTRIES => [
                    'home',
                    'blogname',
                    'blogdescription',
                ],
                self::OPTION_BEHAVIOR => Behaviors::ALLOWLIST,
            ],
            self::SCHEMA_CUSTOMPOST_META => $defaultMetaValues,
            self::SCHEMA_USER_META => $defaultMetaValues,
            self::SCHEMA_COMMENT_META => $defaultMetaValues,
            self::SCHEMA_TAXONOMY_META => $defaultMetaValues,
        ];
        return $defaultValues[$module][$option];
    }

    /**
     * Array with the inputs to show as settings for the module
     *
     * @return array<array> List of settings for the module, each entry is an array with property => value
     */
    public function getSettings(string $module): array
    {
        $moduleSettings = parent::getSettings($module);
        // Common variables to set the limit on the schema types
        $limitArg = 'limit';
        $unlimitedValue = -1;
        $defaultLimitMessagePlaceholder = \__('Number of results from querying %s when argument <code>%s</code> is not provided. Use <code>%s</code> for unlimited', 'graphql-api');
        $maxLimitMessagePlaceholder = \__('Maximum number of results from querying %s. Use <code>%s</code> for unlimited', 'graphql-api');
        // Do the if one by one, so that the SELECT do not get evaluated unless needed
        if ($module == self::SCHEMA_ADMIN_SCHEMA) {
            $option = self::OPTION_ENABLE_ADMIN_SCHEMA;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Add admin fields to schema?', 'graphql-api'),
                Properties::DESCRIPTION => \__('Add "unrestricted" fields to the GraphQL schema (such as <code>Root.unrestrictedPosts</code>, <code>Root.roles</code>, and others), to be used by the admin only.<hr/><strong>Watch out: Enable only if needed!</strong><br/>These fields can expose sensitive information, so they should be enabled only when the API is not publicly exposed (such as when using a local WordPress instance, to build a static site).<br/><br/><strong>Heads up!</strong><br/>If you need some fields but not others, then click the checkbox to enable all the "admin" fields, and then remove the unneeded fields via an Access Control List.', 'graphql-api'),
                Properties::TYPE => Properties::TYPE_BOOL,
            ];
        } elseif (
            in_array($module, [
                self::SCHEMA_CUSTOMPOSTS,
                // self::SCHEMA_GENERIC_CUSTOMPOSTS,
                // self::SCHEMA_POSTS,
                self::SCHEMA_USERS,
                self::SCHEMA_TAGS,
                self::SCHEMA_CATEGORIES,
                // self::SCHEMA_PAGES,
            ])
        ) {
            $moduleEntries = [
                self::SCHEMA_CUSTOMPOSTS => [
                    'entities' => \__('custom posts', 'graphql-api'),
                ],
                // self::SCHEMA_GENERIC_CUSTOMPOSTS => [
                //     'genericCustomPosts' => null,
                // ],
                // self::SCHEMA_POSTS => [
                //     'posts' => null,
                // ],
                self::SCHEMA_USERS => [
                    'entities' => \__('users', 'graphql-api'),
                ],
                self::SCHEMA_TAGS => [
                    'entities' => \__('tags', 'graphql-api'),
                ],
                self::SCHEMA_CATEGORIES => [
                    'entities' => \__('categories', 'graphql-api'),
                ],
                // self::SCHEMA_PAGES => [
                //     'pages' => null,
                // ],
            ];
            $moduleEntry = $moduleEntries[$module];
            // If the options is not provided, use the default one
            $entities = $moduleEntry['entities'];
            $options = $moduleEntry['options'] ?? [
                self::OPTION_LIST_DEFAULT_LIMIT,
                self::OPTION_LIST_MAX_LIMIT,
            ];
            list(
                $defaultLimitOption,
                $maxLimitOption,
            ) = $options;
            $moduleSettings[] = [
                Properties::INPUT => $defaultLimitOption,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $defaultLimitOption
                ),
                Properties::TITLE => sprintf(
                    \__('Default limit for %s', 'graphql-api'),
                    $entities
                ),
                Properties::DESCRIPTION => sprintf(
                    $defaultLimitMessagePlaceholder,
                    $entities,
                    $limitArg,
                    $unlimitedValue
                ),
                Properties::TYPE => Properties::TYPE_INT,
                Properties::MIN_NUMBER => -1,
            ];
            $moduleSettings[] = [
                Properties::INPUT => $maxLimitOption,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $maxLimitOption
                ),
                Properties::TITLE => sprintf(
                    \__('Max limit for %s', 'graphql-api'),
                    $entities
                ),
                Properties::DESCRIPTION => sprintf(
                    $maxLimitMessagePlaceholder,
                    $entities,
                    $unlimitedValue
                ),
                Properties::TYPE => Properties::TYPE_INT,
                Properties::MIN_NUMBER => -1,
            ];

            if ($module == self::SCHEMA_CUSTOMPOSTS) {
                $option = self::OPTION_USE_SINGLE_TYPE_INSTEAD_OF_UNION_TYPE;
                $moduleSettings[] = [
                    Properties::INPUT => $option,
                    Properties::NAME => $this->getSettingOptionName(
                        $module,
                        $option
                    ),
                    Properties::TITLE => \__('Use single type instead of union type?', 'graphql-api'),
                    Properties::DESCRIPTION => sprintf(
                        \__('If type <code>%s</code> is composed of only one type (eg: <code>%s</code>), then return this single type directly in field <code>%s</code>?', 'graphql-api'),
                        $this->customPostUnionTypeResolver->getTypeName(),
                        $this->postTypeResolver->getTypeName(),
                        'customPosts'
                    ),
                    Properties::TYPE => Properties::TYPE_BOOL,
                ];
            }
        } elseif (
            in_array($module, [
                self::SCHEMA_POSTS,
                self::SCHEMA_PAGES,
            ])
        ) {
            $titlePlaceholder = sprintf(
                \__('Include type <code>%1$s</code> in <code>%2$s</code>?', 'graphql-api'),
                '%1$s',
                $this->customPostUnionTypeResolver->getTypeName()
            );
            $moduleTitles = [
                self::SCHEMA_POSTS => sprintf(
                    $titlePlaceholder,
                    $this->postTypeResolver->getTypeName()
                ),
                self::SCHEMA_PAGES => sprintf(
                    $titlePlaceholder,
                    $this->pageTypeResolver->getTypeName()
                ),
            ];
            $descriptionPlaceholder = sprintf(
                \__('Results of type <code>%1$s</code> will be included when querying a field of type <code>%2$s</code> (such as <code>%3$s</code>)', 'graphql-api'),
                '%1$s',
                $this->customPostUnionTypeResolver->getTypeName(),
                'customPosts'
            );
            $moduleDescriptions = [
                self::SCHEMA_POSTS => sprintf(
                    $descriptionPlaceholder,
                    $this->postTypeResolver->getTypeName()
                ),
                self::SCHEMA_PAGES => sprintf(
                    $descriptionPlaceholder,
                    $this->pageTypeResolver->getTypeName()
                ),
            ];
            $option = self::OPTION_ADD_TYPE_TO_CUSTOMPOST_UNION_TYPE;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => $moduleTitles[$module],
                Properties::DESCRIPTION => $moduleDescriptions[$module],
                Properties::TYPE => Properties::TYPE_BOOL,
            ];
        } elseif ($module == self::SCHEMA_GENERIC_CUSTOMPOSTS) {
            // Get the list of custom post types from the system
            $genericCustomPostTypes = \get_post_types();
            // Not all custom post types make sense or are allowed.
            // Remove the ones that do not
            $genericCustomPostTypes = array_values(array_diff(
                $genericCustomPostTypes,
                [
                    // Post Types from GraphQL API that contain private data
                    GraphQLAccessControlListCustomPostType::CUSTOM_POST_TYPE,
                    GraphQLCacheControlListCustomPostType::CUSTOM_POST_TYPE,
                    GraphQLFieldDeprecationListCustomPostType::CUSTOM_POST_TYPE,
                    GraphQLSchemaConfigurationCustomPostType::CUSTOM_POST_TYPE,
                    GraphQLEndpointCustomPostType::CUSTOM_POST_TYPE,
                    GraphQLPersistedQueryCustomPostType::CUSTOM_POST_TYPE,
                    // WordPress internal CPTs
                    // Attachment not allowed because its post_status="inherit",
                    // not "publish", and the API filters by "publish" entries
                    'attachment',
                    'revision',
                    'nav_menu_item',
                    'custom_css',
                    'customize_changeset',
                    'oembed_cache',
                    'user_request',
                    'wp_block',
                    'wp_area',
                ]
            ));
            // Allow plugins to remove their own unwanted custom post types
            $genericCustomPostTypes = \apply_filters(
                self::HOOK_GENERIC_CUSTOMPOST_TYPES,
                $genericCustomPostTypes
            );
            // The possible values must have key and value
            $possibleValues = [];
            foreach ($genericCustomPostTypes as $genericCustomPostType) {
                $possibleValues[$genericCustomPostType] = $genericCustomPostType;
            }
            // Set the setting
            $option = self::OPTION_CUSTOMPOST_TYPES;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Included custom post types', 'graphql-api'),
                Properties::DESCRIPTION => sprintf(
                    \__('Results from these custom post types will be included when querying a field with type <code>%s</code> (such as <code>%s</code>)<br/>Press <code>ctrl</code> or <code>shift</code> keys to select more than one', 'graphql-api'),
                    $this->genericCustomPostTypeResolver->getTypeName(),
                    'genericCustomPosts'
                ),
                Properties::TYPE => Properties::TYPE_ARRAY,
                // Fetch all Schema Configurations from the DB
                Properties::POSSIBLE_VALUES => $possibleValues,
                Properties::IS_MULTIPLE => true,
            ];
        } elseif (
            in_array($module, [
                self::SCHEMA_SETTINGS,
                self::SCHEMA_CUSTOMPOST_META,
                self::SCHEMA_USER_META,
                self::SCHEMA_COMMENT_META,
                self::SCHEMA_TAXONOMY_META,
            ])
        ) {
            $entriesTitle = $module === self::SCHEMA_SETTINGS ?
                \__('Settings entries', 'graphql-api')
                : \__('Meta keys', 'graphql-api');
            $metaKeyDesc = \__('List of all the meta keys, to either allow or deny access to, when querying field <code>meta</code> on %s.', 'graphql-api');
            $headsUpDesc = sprintf(
                \__('<strong>Heads up:</strong> Entries surrounded with <code>/</code> are evaluated as regex (regular expressions).', 'graphql-api'),
                'option',
            );
            $entryDesc = \__('Eg: Both entries <code>%1$s</code> and <code>/%2$s.*/</code> match option name <code>"%1$s"</code>.', 'graphql-api');
            $moduleDescriptions = [
                self::SCHEMA_SETTINGS => sprintf(
                    \__('%1$s<hr/>%2$s<br/>%3$s', 'graphql-api'),
                    sprintf(
                        \__('List of all the option names, to either allow or deny access to, when querying field <code>%s</code>.', 'graphql-api'),
                        'option'
                    ),
                    $headsUpDesc,
                    sprintf(
                        $entryDesc,
                        'siteurl',
                        'site'
                    )
                ),
                self::SCHEMA_CUSTOMPOST_META => sprintf(
                    \__('%1$s<hr/>%2$s<br/>%3$s', 'graphql-api'),
                    sprintf(
                        $metaKeyDesc,
                        'custom posts'
                    ),
                    $headsUpDesc,
                    sprintf(
                        $entryDesc,
                        '_edit_last',
                        '_edit_'
                    )
                ),
                self::SCHEMA_USER_META => sprintf(
                    \__('%1$s<hr/>%2$s<br/>%3$s', 'graphql-api'),
                    sprintf(
                        $metaKeyDesc,
                        'users'
                    ),
                    $headsUpDesc,
                    sprintf(
                        $entryDesc,
                        'last_name',
                        'last_'
                    )
                ),
                self::SCHEMA_COMMENT_META => sprintf(
                    \__('%1$s<hr/>%2$s<br/>%3$s', 'graphql-api'),
                    sprintf(
                        $metaKeyDesc,
                        'comments'
                    ),
                    $headsUpDesc,
                    sprintf(
                        $entryDesc,
                        'description',
                        'desc'
                    )
                ),
                self::SCHEMA_TAXONOMY_META => sprintf(
                    \__('%1$s<hr/>%2$s<br/>%3$s', 'graphql-api'),
                    sprintf(
                        $metaKeyDesc,
                        'taxonomies (tags and categories)'
                    ),
                    $headsUpDesc,
                    sprintf(
                        $entryDesc,
                        'description',
                        'desc'
                    )
                ),
            ];
            $option = self::OPTION_ENTRIES;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => $entriesTitle,
                Properties::DESCRIPTION => $moduleDescriptions[$module],
                Properties::TYPE => Properties::TYPE_ARRAY,
            ];

            $option = self::OPTION_BEHAVIOR;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Behavior', 'graphql-api'),
                Properties::DESCRIPTION => \__('Are the entries being allowed or denied?<ul><li>👉🏽 Allow access: only the configured entries can be accessed, and no other can.</li><li>👉🏽 Deny access: the configured entries cannot be accessed, all other entries can.</li></ul>', 'graphql-api'),
                Properties::TYPE => Properties::TYPE_STRING,
                Properties::POSSIBLE_VALUES => [
                    Behaviors::ALLOWLIST => \__('Allow access', 'graphql-api'),
                    Behaviors::DENYLIST => \__('Deny access', 'graphql-api'),
                ],
            ];
        }

        return $moduleSettings;
    }
}
