<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\Constants\ModuleSettingOptions;
use GraphQLAPI\GraphQLAPI\ContentProcessors\MarkdownContentParserInterface;
use GraphQLAPI\GraphQLAPI\ModuleSettings\Properties;
use GraphQLAPI\GraphQLAPI\Plugin;
use GraphQLAPI\GraphQLAPI\PluginEnvironment;
use GraphQLAPI\GraphQLAPI\Registries\CustomPostTypeRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\CustomPostTypeInterface;
use PoPCMSSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\UnionType\CustomPostUnionTypeResolver;
use PoPCMSSchema\GenericCustomPosts\TypeResolvers\ObjectType\GenericCustomPostObjectTypeResolver;
use PoPCMSSchema\Media\TypeResolvers\ObjectType\MediaObjectTypeResolver;
use PoPCMSSchema\Menus\TypeResolvers\ObjectType\MenuObjectTypeResolver;
use PoPCMSSchema\Pages\TypeResolvers\ObjectType\PageObjectTypeResolver;
use PoPCMSSchema\PostCategories\TypeResolvers\ObjectType\PostCategoryObjectTypeResolver;
use PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoPCMSSchema\PostTags\TypeResolvers\ObjectType\PostTagObjectTypeResolver;
use PoPSchema\SchemaCommons\Constants\Behaviors;
use PoPCMSSchema\UserAvatars\TypeResolvers\ObjectType\UserAvatarObjectTypeResolver;
use PoPCMSSchema\UserRolesWP\TypeResolvers\ObjectType\UserRoleObjectTypeResolver;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class SchemaTypeModuleResolver extends AbstractModuleResolver
{
    use ModuleResolverTrait {
        ModuleResolverTrait::hasDocumentation as upstreamHasDocumentation;
    }
    use SchemaTypeModuleResolverTrait;

    public final const SCHEMA_EXPOSE_ADMIN_DATA = Plugin::NAMESPACE . '\schema-expose-admin-data';
    public final const SCHEMA_CUSTOMPOSTS = Plugin::NAMESPACE . '\schema-customposts';
    public final const SCHEMA_GENERIC_CUSTOMPOSTS = Plugin::NAMESPACE . '\schema-generic-customposts';
    public final const SCHEMA_POSTS = Plugin::NAMESPACE . '\schema-posts';
    public final const SCHEMA_COMMENTS = Plugin::NAMESPACE . '\schema-comments';
    public final const SCHEMA_USERS = Plugin::NAMESPACE . '\schema-users';
    public final const SCHEMA_USER_ROLES = Plugin::NAMESPACE . '\schema-user-roles';
    public final const SCHEMA_USER_AVATARS = Plugin::NAMESPACE . '\schema-user-avatars';
    public final const SCHEMA_PAGES = Plugin::NAMESPACE . '\schema-pages';
    public final const SCHEMA_MEDIA = Plugin::NAMESPACE . '\schema-media';
    public final const SCHEMA_TAGS = Plugin::NAMESPACE . '\schema-tags';
    public final const SCHEMA_POST_TAGS = Plugin::NAMESPACE . '\schema-post-tags';
    public final const SCHEMA_CATEGORIES = Plugin::NAMESPACE . '\schema-categories';
    public final const SCHEMA_POST_CATEGORIES = Plugin::NAMESPACE . '\schema-post-categories';
    public final const SCHEMA_MENUS = Plugin::NAMESPACE . '\schema-menus';
    public final const SCHEMA_SETTINGS = Plugin::NAMESPACE . '\schema-settings';

    /**
     * Setting options
     */
    public final const OPTION_USE_SINGLE_TYPE_INSTEAD_OF_UNION_TYPE = 'use-single-type-instead-of-union-type';
    public final const OPTION_DEFAULT_AVATAR_SIZE = 'default-avatar-size';
    public final const OPTION_ROOT_COMMENT_LIST_DEFAULT_LIMIT = 'root-comment-list-default-limit';
    public final const OPTION_CUSTOMPOST_COMMENT_OR_COMMENT_RESPONSE_LIST_DEFAULT_LIMIT = 'custompost-comment-list-default-limit';
    public final const OPTION_TREAT_CUSTOMPOST_STATUS_AS_ADMIN_DATA = 'treat-custompost-status-as-admin-data';
    public final const OPTION_TREAT_COMMENT_STATUS_AS_ADMIN_DATA = 'treat-comment-status-as-admin-data';
    public final const OPTION_TREAT_USER_EMAIL_AS_ADMIN_DATA = 'treat-user-email-as-admin-data';
    public final const OPTION_TREAT_USER_ROLE_AS_ADMIN_DATA = 'treat-user-role-as-admin-data';
    public final const OPTION_TREAT_USER_CAPABILITY_AS_ADMIN_DATA = 'treat-user-capability-as-admin-data';

    /**
     * Hooks
     */
    public final const HOOK_GENERIC_CUSTOMPOST_TYPES = __CLASS__ . ':generic-custompost-types';
    public final const HOOK_REJECTED_GENERIC_CUSTOMPOST_TYPES = __CLASS__ . ':rejected-generic-custompost-types';

    /**
     * This comment used to be valid when using `autowire` functions
     * to automatically inject all services. Since migrating to lazy getters,
     * this same behavior is implicitly covered.
     *
     * Make all properties nullable, becase the ModuleRegistry is registered
     * in the SystemContainer, where there are no typeResolvers so it will be null,
     * and in the ApplicationContainer, from where the "Modules" page is resolved
     * and which does have all the typeResolvers.
     * Function `getDescription` will only be accessed from the Application Container,
     * so the properties will not be null in that situation.
     */
    private ?CommentObjectTypeResolver $commentObjectTypeResolver = null;
    private ?CustomPostUnionTypeResolver $customPostUnionTypeResolver = null;
    private ?GenericCustomPostObjectTypeResolver $genericCustomPostObjectTypeResolver = null;
    private ?MediaObjectTypeResolver $mediaObjectTypeResolver = null;
    private ?PageObjectTypeResolver $pageObjectTypeResolver = null;
    private ?PostTagObjectTypeResolver $postTagObjectTypeResolver = null;
    private ?PostCategoryObjectTypeResolver $postCategoryObjectTypeResolver = null;
    private ?MenuObjectTypeResolver $menuObjectTypeResolver = null;
    private ?PostObjectTypeResolver $postObjectTypeResolver = null;
    private ?UserRoleObjectTypeResolver $userRoleObjectTypeResolver = null;
    private ?UserAvatarObjectTypeResolver $userAvatarObjectTypeResolver = null;
    private ?UserObjectTypeResolver $userObjectTypeResolver = null;
    private ?CustomPostTypeRegistryInterface $customPostTypeRegistry = null;
    private ?MarkdownContentParserInterface $markdownContentParser = null;

    final public function setCommentObjectTypeResolver(CommentObjectTypeResolver $commentObjectTypeResolver): void
    {
        $this->commentObjectTypeResolver = $commentObjectTypeResolver;
    }
    final protected function getCommentObjectTypeResolver(): CommentObjectTypeResolver
    {
        return $this->commentObjectTypeResolver ??= $this->instanceManager->getInstance(CommentObjectTypeResolver::class);
    }
    final public function setCustomPostUnionTypeResolver(CustomPostUnionTypeResolver $customPostUnionTypeResolver): void
    {
        $this->customPostUnionTypeResolver = $customPostUnionTypeResolver;
    }
    final protected function getCustomPostUnionTypeResolver(): CustomPostUnionTypeResolver
    {
        return $this->customPostUnionTypeResolver ??= $this->instanceManager->getInstance(CustomPostUnionTypeResolver::class);
    }
    final public function setGenericCustomPostObjectTypeResolver(GenericCustomPostObjectTypeResolver $genericCustomPostObjectTypeResolver): void
    {
        $this->genericCustomPostObjectTypeResolver = $genericCustomPostObjectTypeResolver;
    }
    final protected function getGenericCustomPostObjectTypeResolver(): GenericCustomPostObjectTypeResolver
    {
        return $this->genericCustomPostObjectTypeResolver ??= $this->instanceManager->getInstance(GenericCustomPostObjectTypeResolver::class);
    }
    final public function setMediaObjectTypeResolver(MediaObjectTypeResolver $mediaObjectTypeResolver): void
    {
        $this->mediaObjectTypeResolver = $mediaObjectTypeResolver;
    }
    final protected function getMediaObjectTypeResolver(): MediaObjectTypeResolver
    {
        return $this->mediaObjectTypeResolver ??= $this->instanceManager->getInstance(MediaObjectTypeResolver::class);
    }
    final public function setPageObjectTypeResolver(PageObjectTypeResolver $pageObjectTypeResolver): void
    {
        $this->pageObjectTypeResolver = $pageObjectTypeResolver;
    }
    final protected function getPageObjectTypeResolver(): PageObjectTypeResolver
    {
        return $this->pageObjectTypeResolver ??= $this->instanceManager->getInstance(PageObjectTypeResolver::class);
    }
    final public function setPostTagObjectTypeResolver(PostTagObjectTypeResolver $postTagObjectTypeResolver): void
    {
        $this->postTagObjectTypeResolver = $postTagObjectTypeResolver;
    }
    final protected function getPostTagObjectTypeResolver(): PostTagObjectTypeResolver
    {
        return $this->postTagObjectTypeResolver ??= $this->instanceManager->getInstance(PostTagObjectTypeResolver::class);
    }
    final public function setPostCategoryObjectTypeResolver(PostCategoryObjectTypeResolver $postCategoryObjectTypeResolver): void
    {
        $this->postCategoryObjectTypeResolver = $postCategoryObjectTypeResolver;
    }
    final protected function getPostCategoryObjectTypeResolver(): PostCategoryObjectTypeResolver
    {
        return $this->postCategoryObjectTypeResolver ??= $this->instanceManager->getInstance(PostCategoryObjectTypeResolver::class);
    }
    final public function setMenuObjectTypeResolver(MenuObjectTypeResolver $menuObjectTypeResolver): void
    {
        $this->menuObjectTypeResolver = $menuObjectTypeResolver;
    }
    final protected function getMenuObjectTypeResolver(): MenuObjectTypeResolver
    {
        return $this->menuObjectTypeResolver ??= $this->instanceManager->getInstance(MenuObjectTypeResolver::class);
    }
    final public function setPostObjectTypeResolver(PostObjectTypeResolver $postObjectTypeResolver): void
    {
        $this->postObjectTypeResolver = $postObjectTypeResolver;
    }
    final protected function getPostObjectTypeResolver(): PostObjectTypeResolver
    {
        return $this->postObjectTypeResolver ??= $this->instanceManager->getInstance(PostObjectTypeResolver::class);
    }
    final public function setUserRoleObjectTypeResolver(UserRoleObjectTypeResolver $userRoleObjectTypeResolver): void
    {
        $this->userRoleObjectTypeResolver = $userRoleObjectTypeResolver;
    }
    final protected function getUserRoleObjectTypeResolver(): UserRoleObjectTypeResolver
    {
        return $this->userRoleObjectTypeResolver ??= $this->instanceManager->getInstance(UserRoleObjectTypeResolver::class);
    }
    final public function setUserAvatarObjectTypeResolver(UserAvatarObjectTypeResolver $userAvatarObjectTypeResolver): void
    {
        $this->userAvatarObjectTypeResolver = $userAvatarObjectTypeResolver;
    }
    final protected function getUserAvatarObjectTypeResolver(): UserAvatarObjectTypeResolver
    {
        return $this->userAvatarObjectTypeResolver ??= $this->instanceManager->getInstance(UserAvatarObjectTypeResolver::class);
    }
    final public function setUserObjectTypeResolver(UserObjectTypeResolver $userObjectTypeResolver): void
    {
        $this->userObjectTypeResolver = $userObjectTypeResolver;
    }
    final protected function getUserObjectTypeResolver(): UserObjectTypeResolver
    {
        return $this->userObjectTypeResolver ??= $this->instanceManager->getInstance(UserObjectTypeResolver::class);
    }
    final public function setCustomPostTypeRegistry(CustomPostTypeRegistryInterface $customPostTypeRegistry): void
    {
        $this->customPostTypeRegistry = $customPostTypeRegistry;
    }
    final protected function getCustomPostTypeRegistry(): CustomPostTypeRegistryInterface
    {
        return $this->customPostTypeRegistry ??= $this->instanceManager->getInstance(CustomPostTypeRegistryInterface::class);
    }
    final public function setMarkdownContentParser(MarkdownContentParserInterface $markdownContentParser): void
    {
        $this->markdownContentParser = $markdownContentParser;
    }
    final protected function getMarkdownContentParser(): MarkdownContentParserInterface
    {
        return $this->markdownContentParser ??= $this->instanceManager->getInstance(MarkdownContentParserInterface::class);
    }

    /**
     * @return string[]
     */
    public function getModulesToResolve(): array
    {
        return [
            self::SCHEMA_EXPOSE_ADMIN_DATA,
            self::SCHEMA_CUSTOMPOSTS,
            self::SCHEMA_GENERIC_CUSTOMPOSTS,
            self::SCHEMA_POSTS,
            self::SCHEMA_PAGES,
            self::SCHEMA_USERS,
            self::SCHEMA_USER_ROLES,
            self::SCHEMA_USER_AVATARS,
            self::SCHEMA_COMMENTS,
            self::SCHEMA_TAGS,
            self::SCHEMA_POST_TAGS,
            self::SCHEMA_CATEGORIES,
            self::SCHEMA_POST_CATEGORIES,
            self::SCHEMA_MEDIA,
            self::SCHEMA_MENUS,
            self::SCHEMA_SETTINGS,
        ];
    }

    /**
     * @return array<array> List of entries that must be satisfied, each entry is an array where at least 1 module must be satisfied
     */
    public function getDependedModuleLists(string $module): array
    {
        switch ($module) {
            case self::SCHEMA_USER_ROLES:
            case self::SCHEMA_USER_AVATARS:
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
        }
        return parent::getDependedModuleLists($module);
    }

    public function getName(string $module): string
    {
        return match ($module) {
            self::SCHEMA_EXPOSE_ADMIN_DATA => \__('Schema Expose Admin Data', 'graphql-api'),
            self::SCHEMA_GENERIC_CUSTOMPOSTS => \__('Schema Generic Custom Posts', 'graphql-api'),
            self::SCHEMA_POSTS => \__('Schema Posts', 'graphql-api'),
            self::SCHEMA_COMMENTS => \__('Schema Comments', 'graphql-api'),
            self::SCHEMA_USERS => \__('Schema Users', 'graphql-api'),
            self::SCHEMA_USER_ROLES => \__('Schema User Roles', 'graphql-api'),
            self::SCHEMA_USER_AVATARS => \__('Schema User Avatars', 'graphql-api'),
            self::SCHEMA_PAGES => \__('Schema Pages', 'graphql-api'),
            self::SCHEMA_MEDIA => \__('Schema Media', 'graphql-api'),
            self::SCHEMA_TAGS => \__('Schema Tags', 'graphql-api'),
            self::SCHEMA_POST_TAGS => \__('Schema Post Tags', 'graphql-api'),
            self::SCHEMA_CATEGORIES => \__('Schema Categories', 'graphql-api'),
            self::SCHEMA_POST_CATEGORIES => \__('Schema Post Categories', 'graphql-api'),
            self::SCHEMA_MENUS => \__('Schema Menus', 'graphql-api'),
            self::SCHEMA_SETTINGS => \__('Schema Settings', 'graphql-api'),
            self::SCHEMA_CUSTOMPOSTS => \__('Schema Custom Posts', 'graphql-api'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        switch ($module) {
            case self::SCHEMA_EXPOSE_ADMIN_DATA:
                return \__('Expose "admin" elements in the schema', 'graphql-api');
            case self::SCHEMA_GENERIC_CUSTOMPOSTS:
                return sprintf(
                    \__('Query any custom post type (added to the schema or not), through a generic type <code>%1$s</code>', 'graphql-api'),
                    $this->getGenericCustomPostObjectTypeResolver()->getTypeName()
                );
            case self::SCHEMA_POSTS:
                return sprintf(
                    \__('Query %1$s, through type <code>%2$s</code> added to the schema', 'graphql-api'),
                    \__('posts', 'graphql-api'),
                    $this->getPostObjectTypeResolver()->getTypeName()
                );
            case self::SCHEMA_USERS:
                return sprintf(
                    \__('Query %1$s, through type <code>%2$s</code> added to the schema', 'graphql-api'),
                    \__('users', 'graphql-api'),
                    $this->getUserObjectTypeResolver()->getTypeName()
                );
            case self::SCHEMA_USER_ROLES:
                return sprintf(
                    \__('Query %1$s, through type <code>%2$s</code> added to the schema', 'graphql-api'),
                    \__('user roles', 'graphql-api'),
                    $this->getUserRoleObjectTypeResolver()->getTypeName()
                );
            case self::SCHEMA_USER_AVATARS:
                return sprintf(
                    \__('Query %1$s, through type <code>%2$s</code> added to the schema', 'graphql-api'),
                    \__('user avatars', 'graphql-api'),
                    $this->getUserAvatarObjectTypeResolver()->getTypeName()
                );
            case self::SCHEMA_PAGES:
                return sprintf(
                    \__('Query %1$s, through type <code>%2$s</code> added to the schema', 'graphql-api'),
                    \__('pages', 'graphql-api'),
                    $this->getPageObjectTypeResolver()->getTypeName()
                );
            case self::SCHEMA_MEDIA:
                return sprintf(
                    \__('Query %1$s, through type <code>%2$s</code> added to the schema', 'graphql-api'),
                    \__('media elements', 'graphql-api'),
                    $this->getMediaObjectTypeResolver()->getTypeName()
                );
            case self::SCHEMA_COMMENTS:
                return sprintf(
                    \__('Query %1$s, through type <code>%2$s</code> added to the schema', 'graphql-api'),
                    \__('comments', 'graphql-api'),
                    $this->getCommentObjectTypeResolver()->getTypeName()
                );
            case self::SCHEMA_POST_TAGS:
                return sprintf(
                    \__('Query %1$s, through type <code>%2$s</code> added to the schema', 'graphql-api'),
                    \__('post tags', 'graphql-api'),
                    $this->getPostTagObjectTypeResolver()->getTypeName()
                );
            case self::SCHEMA_POST_CATEGORIES:
                return sprintf(
                    \__('Query %1$s, through type <code>%2$s</code> added to the schema', 'graphql-api'),
                    \__('post categories', 'graphql-api'),
                    $this->getPostCategoryObjectTypeResolver()->getTypeName()
                );
            case self::SCHEMA_MENUS:
                return sprintf(
                    \__('Query %1$s, through type <code>%2$s</code> added to the schema', 'graphql-api'),
                    \__('menus', 'graphql-api'),
                    $this->getMenuObjectTypeResolver()->getTypeName()
                );
            case self::SCHEMA_SETTINGS:
                return \__('Fetch settings from the site', 'graphql-api');
            case self::SCHEMA_CUSTOMPOSTS:
                return \__('Base functionality for all custom posts', 'graphql-api');
            case self::SCHEMA_TAGS:
                return \__('Base functionality for all tags', 'graphql-api');
            case self::SCHEMA_CATEGORIES:
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
            case self::SCHEMA_USER_AVATARS:
            case self::SCHEMA_COMMENTS:
            case self::SCHEMA_TAGS:
            case self::SCHEMA_POST_TAGS:
            case self::SCHEMA_CATEGORIES:
            case self::SCHEMA_POST_CATEGORIES:
            case self::SCHEMA_MENUS:
            case self::SCHEMA_MEDIA:
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
            (
                in_array(
                    $module,
                    [
                        self::SCHEMA_CUSTOMPOSTS,
                        // self::SCHEMA_GENERIC_CUSTOMPOSTS,
                        self::SCHEMA_POSTS,
                        self::SCHEMA_USERS,
                        self::SCHEMA_MEDIA,
                        self::SCHEMA_MENUS,
                        self::SCHEMA_TAGS,
                        self::SCHEMA_CATEGORIES,
                        self::SCHEMA_PAGES,
                    ]
                ) && in_array(
                    $option,
                    [
                        ModuleSettingOptions::LIST_DEFAULT_LIMIT,
                        ModuleSettingOptions::LIST_MAX_LIMIT,
                    ]
                )
            ) || (
                $module === self::SCHEMA_COMMENTS
                && in_array(
                    $option,
                    [
                        self::OPTION_ROOT_COMMENT_LIST_DEFAULT_LIMIT,
                        self::OPTION_CUSTOMPOST_COMMENT_OR_COMMENT_RESPONSE_LIST_DEFAULT_LIMIT,
                        ModuleSettingOptions::LIST_MAX_LIMIT,
                    ]
                )
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
        // Lower the security constraints for the static app
        $useUnsafe = PluginEnvironment::areUnsafeDefaultsEnabled();
        $defaultValues = [
            self::SCHEMA_EXPOSE_ADMIN_DATA => [
                ModuleSettingOptions::DEFAULT_VALUE => $useUnsafe,
                ModuleSettingOptions::VALUE_FOR_ADMIN_CLIENTS => true,
            ],
            self::SCHEMA_CUSTOMPOSTS => [
                ModuleSettingOptions::LIST_DEFAULT_LIMIT => 10,
                ModuleSettingOptions::LIST_MAX_LIMIT => $useUnsafe ? -1 : 100,
                self::OPTION_USE_SINGLE_TYPE_INSTEAD_OF_UNION_TYPE => false,
                self::OPTION_TREAT_CUSTOMPOST_STATUS_AS_ADMIN_DATA => true,
            ],
            self::SCHEMA_GENERIC_CUSTOMPOSTS => [
                ModuleSettingOptions::CUSTOMPOST_TYPES => ['post'],
            ],
            self::SCHEMA_POSTS => [
                ModuleSettingOptions::LIST_DEFAULT_LIMIT => 10,
                ModuleSettingOptions::LIST_MAX_LIMIT => $useUnsafe ? -1 : 100,
                ModuleSettingOptions::ADD_TYPE_TO_CUSTOMPOST_UNION_TYPE => true,
            ],
            self::SCHEMA_PAGES => [
                ModuleSettingOptions::LIST_DEFAULT_LIMIT => 10,
                ModuleSettingOptions::LIST_MAX_LIMIT => $useUnsafe ? -1 : 100,
                ModuleSettingOptions::ADD_TYPE_TO_CUSTOMPOST_UNION_TYPE => false,
            ],
            self::SCHEMA_USERS => [
                ModuleSettingOptions::LIST_DEFAULT_LIMIT => 10,
                ModuleSettingOptions::LIST_MAX_LIMIT => $useUnsafe ? -1 : 100,
                self::OPTION_TREAT_USER_EMAIL_AS_ADMIN_DATA => true,
            ],
            self::SCHEMA_USER_ROLES => [
                self::OPTION_TREAT_USER_ROLE_AS_ADMIN_DATA => true,
                self::OPTION_TREAT_USER_CAPABILITY_AS_ADMIN_DATA => true,
            ],
            self::SCHEMA_MEDIA => [
                ModuleSettingOptions::LIST_DEFAULT_LIMIT => 10,
                ModuleSettingOptions::LIST_MAX_LIMIT => $useUnsafe ? -1 : 100,
            ],
            self::SCHEMA_MENUS => [
                ModuleSettingOptions::LIST_DEFAULT_LIMIT => 10,
                ModuleSettingOptions::LIST_MAX_LIMIT => $useUnsafe ? -1 : 100,
            ],
            self::SCHEMA_TAGS => [
                ModuleSettingOptions::LIST_DEFAULT_LIMIT => 20,
                ModuleSettingOptions::LIST_MAX_LIMIT => $useUnsafe ? -1 : 200,
            ],
            self::SCHEMA_CATEGORIES => [
                ModuleSettingOptions::LIST_DEFAULT_LIMIT => 20,
                ModuleSettingOptions::LIST_MAX_LIMIT => $useUnsafe ? -1 : 200,
            ],
            self::SCHEMA_SETTINGS => [
                ModuleSettingOptions::ENTRIES => $useUnsafe ? [] : [
                    'siteurl',
                    'home',
                    'blogname',
                    'blogdescription',
                    'WPLANG',
                    'posts_per_page',
                    'comments_per_page',
                    'date_format',
                    'time_format',
                    'blog_charset',
                ],
                ModuleSettingOptions::BEHAVIOR => $useUnsafe ?
                    Behaviors::DENYLIST
                    : Behaviors::ALLOWLIST,
            ],
            self::SCHEMA_USER_AVATARS => [
                self::OPTION_DEFAULT_AVATAR_SIZE => 96,
            ],
            self::SCHEMA_COMMENTS => [
                self::OPTION_ROOT_COMMENT_LIST_DEFAULT_LIMIT => 10,
                self::OPTION_CUSTOMPOST_COMMENT_OR_COMMENT_RESPONSE_LIST_DEFAULT_LIMIT => -1,
                ModuleSettingOptions::LIST_MAX_LIMIT => -1,
                self::OPTION_TREAT_COMMENT_STATUS_AS_ADMIN_DATA => true,
            ],
        ];
        return $defaultValues[$module][$option] ?? null;
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
        $defaultValueLabel = $this->getDefaultValueLabel();
        $defaultValueDesc = $this->getDefaultValueDescription();
        $adminClientsDesc = $this->getAdminClientDescription();
        $privateDataTitlePlaceholder = \__('Treat %s as private data', 'graphql-api');
        $privateDataDescPlaceholder = \__('If checked, the <strong>%s</strong> data is exposed in the schema (whether as an object field for querying, or as an input field for filtering) only if the Schema Configuration has property <code>Schema Expose Admin Data</code> enabled (i.e. the data is for private use only); otherwise, the data is always exposed in the schema (i.e. it is public)', 'graphql-api');
        // Do the if one by one, so that the SELECT do not get evaluated unless needed
        if ($module == self::SCHEMA_EXPOSE_ADMIN_DATA) {
            $option = ModuleSettingOptions::DEFAULT_VALUE;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => sprintf(
                    \__('Add admin fields to schema? %s', 'graphql-api'),
                    $defaultValueLabel
                ),
                Properties::DESCRIPTION => sprintf(
                    \__('Expose "admin" elements in the GraphQL schema (such as field <code>Root.roles</code>, input field <code>Root.posts(status:)</code>, and others), which provide access to private data. %s', 'graphql-api'),
                    $defaultValueDesc
                ),
                Properties::TYPE => Properties::TYPE_BOOL,
            ];
            $option = ModuleSettingOptions::VALUE_FOR_ADMIN_CLIENTS;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Expose admin elements for the Admin?', 'graphql-api'),
                Properties::DESCRIPTION => sprintf(
                    \__('Expose "admin" elements in the wp-admin? %s', 'graphql-api'),
                    $adminClientsDesc
                ),
                Properties::TYPE => Properties::TYPE_BOOL,
            ];
        } elseif (
            in_array($module, [
                self::SCHEMA_CUSTOMPOSTS,
                // self::SCHEMA_GENERIC_CUSTOMPOSTS,
                self::SCHEMA_POSTS,
                self::SCHEMA_USERS,
                self::SCHEMA_MEDIA,
                self::SCHEMA_MENUS,
                self::SCHEMA_TAGS,
                self::SCHEMA_CATEGORIES,
                self::SCHEMA_PAGES,
            ])
        ) {
            $moduleEntities = [
                self::SCHEMA_CUSTOMPOSTS => \__('custom posts', 'graphql-api'),
                self::SCHEMA_POSTS => \__('posts', 'graphql-api'),
                self::SCHEMA_USERS => \__('users', 'graphql-api'),
                self::SCHEMA_MEDIA => \__('media items', 'graphql-api'),
                self::SCHEMA_MENUS => \__('menus', 'graphql-api'),
                self::SCHEMA_TAGS => \__('tags', 'graphql-api'),
                self::SCHEMA_CATEGORIES => \__('categories', 'graphql-api'),
                self::SCHEMA_PAGES => \__('pages', 'graphql-api'),
            ];
            $entities = $moduleEntities[$module];
            $defaultLimitOption = ModuleSettingOptions::LIST_DEFAULT_LIMIT;
            $maxLimitOption = ModuleSettingOptions::LIST_MAX_LIMIT;
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
                        $this->getCustomPostUnionTypeResolver()->getTypeName(),
                        $this->getPostObjectTypeResolver()->getTypeName(),
                        'customPosts'
                    ),
                    Properties::TYPE => Properties::TYPE_BOOL,
                ];

                $option = self::OPTION_TREAT_CUSTOMPOST_STATUS_AS_ADMIN_DATA;
                $moduleSettings[] = [
                    Properties::INPUT => $option,
                    Properties::NAME => $this->getSettingOptionName(
                        $module,
                        $option
                    ),
                    Properties::TITLE => sprintf(
                        $privateDataTitlePlaceholder,
                        \__('custom post status', 'graphql-api'),
                    ),
                    Properties::DESCRIPTION => sprintf(
                        $privateDataDescPlaceholder,
                        \__('custom post status', 'graphql-api'),
                    ),
                    Properties::TYPE => Properties::TYPE_BOOL,
                ];
            } elseif (
                in_array($module, [
                    self::SCHEMA_POSTS,
                    self::SCHEMA_PAGES,
                ])
            ) {
                $titlePlaceholder = sprintf(
                    \__('Include type <code>%1$s</code> in <code>%2$s</code>?', 'graphql-api'),
                    '%1$s',
                    $this->getCustomPostUnionTypeResolver()->getTypeName()
                );
                $moduleTitles = [
                    self::SCHEMA_POSTS => sprintf(
                        $titlePlaceholder,
                        $this->getPostObjectTypeResolver()->getTypeName()
                    ),
                    self::SCHEMA_PAGES => sprintf(
                        $titlePlaceholder,
                        $this->getPageObjectTypeResolver()->getTypeName()
                    ),
                ];
                $descriptionPlaceholder = sprintf(
                    \__('Results of type <code>%1$s</code> will be included when querying a field of type <code>%2$s</code> (such as <code>%3$s</code>)', 'graphql-api'),
                    '%1$s',
                    $this->getCustomPostUnionTypeResolver()->getTypeName(),
                    'customPosts'
                );
                $moduleDescriptions = [
                    self::SCHEMA_POSTS => sprintf(
                        $descriptionPlaceholder,
                        $this->getPostObjectTypeResolver()->getTypeName()
                    ),
                    self::SCHEMA_PAGES => sprintf(
                        $descriptionPlaceholder,
                        $this->getPageObjectTypeResolver()->getTypeName()
                    ),
                ];
                $option = ModuleSettingOptions::ADD_TYPE_TO_CUSTOMPOST_UNION_TYPE;
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
            } elseif ($module == self::SCHEMA_USERS) {
                $option = self::OPTION_TREAT_USER_EMAIL_AS_ADMIN_DATA;
                $moduleSettings[] = [
                    Properties::INPUT => $option,
                    Properties::NAME => $this->getSettingOptionName(
                        $module,
                        $option
                    ),
                    Properties::TITLE => sprintf(
                        $privateDataTitlePlaceholder,
                        \__('user email', 'graphql-api'),
                    ),
                    Properties::DESCRIPTION => sprintf(
                        $privateDataDescPlaceholder,
                        \__('user email', 'graphql-api'),
                    ),
                    Properties::TYPE => Properties::TYPE_BOOL,
                ];
            }
        } elseif ($module === self::SCHEMA_COMMENTS) {
            $option = self::OPTION_ROOT_COMMENT_LIST_DEFAULT_LIMIT;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Default limit for querying comments in the Root', 'graphql-api'),
                Properties::DESCRIPTION => sprintf(
                    $defaultLimitMessagePlaceholder,
                    '<code>Root.comments</code>',
                    $limitArg,
                    $unlimitedValue
                ),
                Properties::TYPE => Properties::TYPE_INT,
                Properties::MIN_NUMBER => -1,
            ];

            $option = self::OPTION_CUSTOMPOST_COMMENT_OR_COMMENT_RESPONSE_LIST_DEFAULT_LIMIT;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Default limit for querying comments under a custom post or comment', 'graphql-api'),
                Properties::DESCRIPTION => sprintf(
                    $defaultLimitMessagePlaceholder,
                    sprintf(
                        \__('%s and %s', 'graphql-api'),
                        '<code>CustomPost.comments</code>',
                        '<code>Comment.responses</code>'
                    ),
                    $limitArg,
                    $unlimitedValue
                ),
                Properties::TYPE => Properties::TYPE_INT,
                Properties::MIN_NUMBER => -1,
            ];

            $option = ModuleSettingOptions::LIST_MAX_LIMIT;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Max limit for querying comments', 'graphql-api'),
                Properties::DESCRIPTION => sprintf(
                    $maxLimitMessagePlaceholder,
                    \__('comments', 'graphql-api'),
                    $unlimitedValue
                ),
                Properties::TYPE => Properties::TYPE_INT,
                Properties::MIN_NUMBER => -1,
            ];

            $option = self::OPTION_TREAT_COMMENT_STATUS_AS_ADMIN_DATA;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => sprintf(
                    $privateDataTitlePlaceholder,
                    \__('comment status', 'graphql-api'),
                ),
                Properties::DESCRIPTION => sprintf(
                    $privateDataDescPlaceholder,
                    \__('comment status', 'graphql-api'),
                ),
                Properties::TYPE => Properties::TYPE_BOOL,
            ];
        } elseif ($module == self::SCHEMA_GENERIC_CUSTOMPOSTS) {
            // Get the list of custom post types from the system
            $genericCustomPostTypes = \get_post_types();
            /**
             * Not all custom post types make sense or are allowed.
             * Remove the ones that do not
             */
            $pluginCustomPostTypes = array_map(
                fn (CustomPostTypeInterface $customPostTypeService) => $customPostTypeService->getCustomPostType(),
                $this->getCustomPostTypeRegistry()->getCustomPostTypes()
            );
            $rejectedGenericCustomPostTypes = \apply_filters(
                self::HOOK_REJECTED_GENERIC_CUSTOMPOST_TYPES,
                array_merge(
                    // Post Types from GraphQL API are just for configuration
                    // and contain private data
                    $pluginCustomPostTypes,
                    // WordPress internal CPTs
                    // Attachment not allowed because its post_status="inherit",
                    // not "publish", and the API filters by "publish" entries
                    [
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
                )
            );
            $genericCustomPostTypes = array_values(array_diff(
                $genericCustomPostTypes,
                $rejectedGenericCustomPostTypes
            ));
            // Allow plugins to further remove unwanted custom post types
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
            $option = ModuleSettingOptions::CUSTOMPOST_TYPES;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Included custom post types', 'graphql-api'),
                Properties::DESCRIPTION => sprintf(
                    \__('Results from these custom post types will be included when querying a field with type <code>%s</code> (such as <code>%s</code>)<br/>Press <code>ctrl</code> or <code>shift</code> keys to select more than one', 'graphql-api'),
                    $this->getGenericCustomPostObjectTypeResolver()->getTypeName(),
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
            ])
        ) {
            $entriesTitle = \__('Settings entries', 'graphql-api');
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
                        'optionValue'
                    ),
                    $headsUpDesc,
                    sprintf(
                        $entryDesc,
                        'siteurl',
                        'site'
                    )
                ),
            ];
            $option = ModuleSettingOptions::ENTRIES;
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

            $option = ModuleSettingOptions::BEHAVIOR;
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
        } elseif ($module == self::SCHEMA_USER_AVATARS) {
            $option = self::OPTION_DEFAULT_AVATAR_SIZE;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Default avatar size', 'graphql-api'),
                Properties::DESCRIPTION => sprintf(
                    \__('Size of the avatar (in pixels) when not providing argument <code>"size"</code> in field <code>%s.avatar</code>', 'graphql-api'),
                    $this->getUserObjectTypeResolver()->getTypeName()
                ),
                Properties::TYPE => Properties::TYPE_INT,
                Properties::MIN_NUMBER => 1,
            ];
        } elseif ($module == self::SCHEMA_USER_ROLES) {
            $option = self::OPTION_TREAT_USER_ROLE_AS_ADMIN_DATA;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => sprintf(
                    $privateDataTitlePlaceholder,
                    \__('user roles', 'graphql-api'),
                ),
                Properties::DESCRIPTION => sprintf(
                    $privateDataDescPlaceholder,
                    \__('user roles', 'graphql-api'),
                ),
                Properties::TYPE => Properties::TYPE_BOOL,
            ];

            $option = self::OPTION_TREAT_USER_CAPABILITY_AS_ADMIN_DATA;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => sprintf(
                    $privateDataTitlePlaceholder,
                    \__('user capabilities', 'graphql-api'),
                ),
                Properties::DESCRIPTION => sprintf(
                    $privateDataDescPlaceholder,
                    \__('user capabilities', 'graphql-api'),
                ),
                Properties::TYPE => Properties::TYPE_BOOL,
            ];
        }

        return $moduleSettings;
    }
}
