<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\Plugin;
use PoPSchema\Pages\TypeResolvers\PageTypeResolver;
use PoPSchema\Posts\TypeResolvers\PostTypeResolver;
use PoPSchema\Users\TypeResolvers\UserTypeResolver;
use GraphQLAPI\GraphQLAPI\ModuleSettings\Properties;
use PoPSchema\Media\TypeResolvers\MediaTypeResolver;
use PoPSchema\Comments\TypeResolvers\CommentTypeResolver;
use PoPSchema\PostTags\TypeResolvers\PostTagTypeResolver;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLEndpointCustomPostType;
use PoPSchema\UserRolesWP\TypeResolvers\UserRoleTypeResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ModuleResolverTrait;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLPersistedQueryCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLCacheControlListCustomPostType;
use PoPSchema\CustomPosts\TypeResolvers\CustomPostUnionTypeResolver;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLAccessControlListCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLSchemaConfigurationCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLFieldDeprecationListCustomPostType;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\AbstractSchemaTypeModuleResolver;
use PoPSchema\GenericCustomPosts\TypeResolvers\GenericCustomPostTypeResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\OperationalFunctionalityModuleResolver;

class SchemaTypeModuleResolver extends AbstractSchemaTypeModuleResolver
{
    use ModuleResolverTrait {
        ModuleResolverTrait::hasDocumentation as upstreamHasDocumentation;
    }

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
    public const SCHEMA_USER_STATE_MUTATIONS = Plugin::NAMESPACE . '\schema-user-state-mutations';
    public const SCHEMA_CUSTOMPOST_MUTATIONS = Plugin::NAMESPACE . '\schema-custompost-mutations';
    public const SCHEMA_POST_MUTATIONS = Plugin::NAMESPACE . '\schema-post-mutations';
    public const SCHEMA_CUSTOMPOSTMEDIA_MUTATIONS = Plugin::NAMESPACE . '\schema-custompostmedia-mutations';
    public const SCHEMA_COMMENT_MUTATIONS = Plugin::NAMESPACE . '\schema-comment-mutations';

    /**
     * Setting options
     */
    public const OPTION_LIST_DEFAULT_LIMIT = 'list-default-limit';
    public const OPTION_LIST_MAX_LIMIT = 'list-max-limit';
    public const OPTION_ADD_TYPE_TO_CUSTOMPOST_UNION_TYPE = 'add-type-to-custompost-union-type';
    public const OPTION_USE_SINGLE_TYPE_INSTEAD_OF_UNION_TYPE = 'use-single-type-instead-of-union-type';
    public const OPTION_CUSTOMPOST_TYPES = 'custompost-types';

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
        protected ?CommentTypeResolver $commentTypeResolver,
        protected ?CustomPostUnionTypeResolver $customPostUnionTypeResolver,
        protected ?GenericCustomPostTypeResolver $genericCustomPostTypeResolver,
        protected ?MediaTypeResolver $mediaTypeResolver,
        protected ?PageTypeResolver $pageTypeResolver,
        protected ?PostTagTypeResolver $postTagTypeResolver,
        protected ?PostTypeResolver $postTypeResolver,
        protected ?UserRoleTypeResolver $userRoleTypeResolver,
        protected ?UserTypeResolver $userTypeResolver
    ) {
    }

    /**
     * @return string[]
     */
    public function getModulesToResolve(): array
    {
        return [
            self::SCHEMA_CUSTOMPOSTS,
            self::SCHEMA_GENERIC_CUSTOMPOSTS,
            self::SCHEMA_POSTS,
            self::SCHEMA_PAGES,
            self::SCHEMA_USERS,
            self::SCHEMA_USER_ROLES,
            self::SCHEMA_COMMENTS,
            self::SCHEMA_TAGS,
            self::SCHEMA_POST_TAGS,
            self::SCHEMA_MEDIA,
            self::SCHEMA_USER_STATE_MUTATIONS,
            self::SCHEMA_CUSTOMPOST_MUTATIONS,
            self::SCHEMA_POST_MUTATIONS,
            self::SCHEMA_CUSTOMPOSTMEDIA_MUTATIONS,
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
            case self::SCHEMA_USERS:
            case self::SCHEMA_MEDIA:
            case self::SCHEMA_CUSTOMPOSTS:
                return [
                    [
                        EndpointFunctionalityModuleResolver::SINGLE_ENDPOINT,
                        EndpointFunctionalityModuleResolver::PERSISTED_QUERIES,
                        EndpointFunctionalityModuleResolver::CUSTOM_ENDPOINTS,
                    ],
                ];
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
            case self::SCHEMA_USER_STATE_MUTATIONS:
                return [
                    [
                        OperationalFunctionalityModuleResolver::MUTATIONS,
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
            self::SCHEMA_GENERIC_CUSTOMPOSTS => \__('Schema Generic Custom Posts', 'graphql-api'),
            self::SCHEMA_POSTS => \__('Schema Posts', 'graphql-api'),
            self::SCHEMA_COMMENTS => \__('Schema Comments', 'graphql-api'),
            self::SCHEMA_USERS => \__('Schema Users', 'graphql-api'),
            self::SCHEMA_USER_ROLES => \__('Schema User Roles', 'graphql-api'),
            self::SCHEMA_PAGES => \__('Schema Pages', 'graphql-api'),
            self::SCHEMA_MEDIA => \__('Schema Media', 'graphql-api'),
            self::SCHEMA_TAGS => \__('Schema Tags', 'graphql-api'),
            self::SCHEMA_POST_TAGS => \__('Schema Post Tags', 'graphql-api'),
            self::SCHEMA_CUSTOMPOSTS => \__('Schema Custom Posts', 'graphql-api'),
            self::SCHEMA_USER_STATE_MUTATIONS => \__('Schema User State Mutations', 'graphql-api'),
            self::SCHEMA_CUSTOMPOST_MUTATIONS => \__('Schema Custom Post Mutations', 'graphql-api'),
            self::SCHEMA_POST_MUTATIONS => \__('Schema Post Mutations', 'graphql-api'),
            self::SCHEMA_CUSTOMPOSTMEDIA_MUTATIONS => \__('Schema Custom Post Media Mutations', 'graphql-api'),
            self::SCHEMA_COMMENT_MUTATIONS => \__('Schema Comment Mutations', 'graphql-api'),
        ];
        return $names[$module] ?? $module;
    }

    public function getDescription(string $module): string
    {
        switch ($module) {
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
            case self::SCHEMA_CUSTOMPOSTS:
                return \__('Base functionality for all custom posts', 'graphql-api');
            case self::SCHEMA_TAGS:
                return \__('Base functionality for all tags', 'graphql-api');
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
            case self::SCHEMA_COMMENT_MUTATIONS:
                return \__('Create comments', 'graphql-api');
        }
        return parent::getDescription($module);
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
            case self::SCHEMA_MEDIA:
            case self::SCHEMA_CUSTOMPOST_MUTATIONS:
            case self::SCHEMA_POST_MUTATIONS:
            case self::SCHEMA_CUSTOMPOSTMEDIA_MUTATIONS:
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
        $defaultValues = [
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
        if (
            in_array($module, [
                self::SCHEMA_CUSTOMPOSTS,
                // self::SCHEMA_GENERIC_CUSTOMPOSTS,
                // self::SCHEMA_POSTS,
                self::SCHEMA_USERS,
                self::SCHEMA_TAGS,
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
        }

        return $moduleSettings;
    }
}
