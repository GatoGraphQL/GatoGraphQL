<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\Constants\ConfigurationDefaultValues;
use GatoGraphQL\GatoGraphQL\Constants\ModuleSettingOptions;
use GatoGraphQL\GatoGraphQL\ContentProcessors\MarkdownContentParserInterface;
use GatoGraphQL\GatoGraphQL\ModuleSettings\Properties;
use GatoGraphQL\GatoGraphQL\Plugin;
use GatoGraphQL\GatoGraphQL\StaticHelpers\BehaviorHelpers;
use GatoGraphQL\GatoGraphQL\WPDataModel\WPDataModelProviderInterface;
use PoPCMSSchema\Categories\TypeResolvers\ObjectType\GenericCategoryObjectTypeResolver;
use PoPCMSSchema\Categories\TypeResolvers\UnionType\CategoryUnionTypeResolver;
use PoPCMSSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\GenericCustomPostObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\UnionType\CustomPostUnionTypeResolver;
use PoPCMSSchema\Media\TypeResolvers\ObjectType\MediaObjectTypeResolver;
use PoPCMSSchema\Menus\TypeResolvers\ObjectType\MenuObjectTypeResolver;
use PoPCMSSchema\Pages\TypeResolvers\ObjectType\PageObjectTypeResolver;
use PoPCMSSchema\PostCategories\TypeResolvers\ObjectType\PostCategoryObjectTypeResolver;
use PoPCMSSchema\PostTags\TypeResolvers\ObjectType\PostTagObjectTypeResolver;
use PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoPCMSSchema\Tags\TypeResolvers\ObjectType\GenericTagObjectTypeResolver;
use PoPCMSSchema\Tags\TypeResolvers\UnionType\TagUnionTypeResolver;
use PoPCMSSchema\UserAvatars\TypeResolvers\ObjectType\UserAvatarObjectTypeResolver;
use PoPCMSSchema\UserRolesWP\TypeResolvers\ObjectType\UserRoleObjectTypeResolver;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;
use PoPSchema\SchemaCommons\Constants\Behaviors;

class SchemaTypeModuleResolver extends AbstractModuleResolver
{
    use ModuleResolverTrait {
        ModuleResolverTrait::hasDocumentation as upstreamHasDocumentation;
    }
    use SchemaTypeModuleResolverTrait;

    public final const SCHEMA_CUSTOMPOSTS = Plugin::NAMESPACE . '\schema-customposts';
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
    public final const OPTION_TREAT_CUSTOMPOST_STATUS_AS_SENSITIVE_DATA = 'treat-custompost-status-as-admin-data';
    public final const OPTION_TREAT_COMMENT_STATUS_AS_SENSITIVE_DATA = 'treat-comment-status-as-admin-data';
    public final const OPTION_TREAT_USER_EMAIL_AS_SENSITIVE_DATA = 'treat-user-email-as-admin-data';
    public final const OPTION_TREAT_USER_ROLE_AS_SENSITIVE_DATA = 'treat-user-role-as-admin-data';
    public final const OPTION_TREAT_USER_CAPABILITY_AS_SENSITIVE_DATA = 'treat-user-capability-as-admin-data';

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
    private ?TagUnionTypeResolver $tagUnionTypeResolver = null;
    private ?CategoryUnionTypeResolver $categoryUnionTypeResolver = null;
    private ?MediaObjectTypeResolver $mediaObjectTypeResolver = null;
    private ?PageObjectTypeResolver $pageObjectTypeResolver = null;
    private ?GenericCustomPostObjectTypeResolver $genericCustomPostObjectTypeResolver = null;
    private ?GenericTagObjectTypeResolver $genericTagObjectTypeResolver = null;
    private ?GenericCategoryObjectTypeResolver $genericCategoryObjectTypeResolver = null;
    private ?PostTagObjectTypeResolver $postTagObjectTypeResolver = null;
    private ?PostCategoryObjectTypeResolver $postCategoryObjectTypeResolver = null;
    private ?MenuObjectTypeResolver $menuObjectTypeResolver = null;
    private ?PostObjectTypeResolver $postObjectTypeResolver = null;
    private ?UserRoleObjectTypeResolver $userRoleObjectTypeResolver = null;
    private ?UserAvatarObjectTypeResolver $userAvatarObjectTypeResolver = null;
    private ?UserObjectTypeResolver $userObjectTypeResolver = null;
    private ?WPDataModelProviderInterface $wpDataModelProvider = null;
    private ?MarkdownContentParserInterface $markdownContentParser = null;

    final public function setCommentObjectTypeResolver(CommentObjectTypeResolver $commentObjectTypeResolver): void
    {
        $this->commentObjectTypeResolver = $commentObjectTypeResolver;
    }
    final protected function getCommentObjectTypeResolver(): CommentObjectTypeResolver
    {
        /** @var CommentObjectTypeResolver */
        return $this->commentObjectTypeResolver ??= $this->instanceManager->getInstance(CommentObjectTypeResolver::class);
    }
    final public function setCustomPostUnionTypeResolver(CustomPostUnionTypeResolver $customPostUnionTypeResolver): void
    {
        $this->customPostUnionTypeResolver = $customPostUnionTypeResolver;
    }
    final protected function getCustomPostUnionTypeResolver(): CustomPostUnionTypeResolver
    {
        /** @var CustomPostUnionTypeResolver */
        return $this->customPostUnionTypeResolver ??= $this->instanceManager->getInstance(CustomPostUnionTypeResolver::class);
    }
    final public function setTagUnionTypeResolver(TagUnionTypeResolver $tagUnionTypeResolver): void
    {
        $this->tagUnionTypeResolver = $tagUnionTypeResolver;
    }
    final protected function getTagUnionTypeResolver(): TagUnionTypeResolver
    {
        /** @var TagUnionTypeResolver */
        return $this->tagUnionTypeResolver ??= $this->instanceManager->getInstance(TagUnionTypeResolver::class);
    }
    final public function setCategoryUnionTypeResolver(CategoryUnionTypeResolver $categoryUnionTypeResolver): void
    {
        $this->categoryUnionTypeResolver = $categoryUnionTypeResolver;
    }
    final protected function getCategoryUnionTypeResolver(): CategoryUnionTypeResolver
    {
        /** @var CategoryUnionTypeResolver */
        return $this->categoryUnionTypeResolver ??= $this->instanceManager->getInstance(CategoryUnionTypeResolver::class);
    }
    final public function setMediaObjectTypeResolver(MediaObjectTypeResolver $mediaObjectTypeResolver): void
    {
        $this->mediaObjectTypeResolver = $mediaObjectTypeResolver;
    }
    final protected function getMediaObjectTypeResolver(): MediaObjectTypeResolver
    {
        /** @var MediaObjectTypeResolver */
        return $this->mediaObjectTypeResolver ??= $this->instanceManager->getInstance(MediaObjectTypeResolver::class);
    }
    final public function setPageObjectTypeResolver(PageObjectTypeResolver $pageObjectTypeResolver): void
    {
        $this->pageObjectTypeResolver = $pageObjectTypeResolver;
    }
    final protected function getPageObjectTypeResolver(): PageObjectTypeResolver
    {
        /** @var PageObjectTypeResolver */
        return $this->pageObjectTypeResolver ??= $this->instanceManager->getInstance(PageObjectTypeResolver::class);
    }
    final public function setGenericCustomPostObjectTypeResolver(GenericCustomPostObjectTypeResolver $genericCustomPostObjectTypeResolver): void
    {
        $this->genericCustomPostObjectTypeResolver = $genericCustomPostObjectTypeResolver;
    }
    final protected function getGenericCustomPostObjectTypeResolver(): GenericCustomPostObjectTypeResolver
    {
        /** @var GenericCustomPostObjectTypeResolver */
        return $this->genericCustomPostObjectTypeResolver ??= $this->instanceManager->getInstance(GenericCustomPostObjectTypeResolver::class);
    }
    final public function setGenericTagObjectTypeResolver(GenericTagObjectTypeResolver $genericTagObjectTypeResolver): void
    {
        $this->genericTagObjectTypeResolver = $genericTagObjectTypeResolver;
    }
    final protected function getGenericTagObjectTypeResolver(): GenericTagObjectTypeResolver
    {
        /** @var GenericTagObjectTypeResolver */
        return $this->genericTagObjectTypeResolver ??= $this->instanceManager->getInstance(GenericTagObjectTypeResolver::class);
    }
    final public function setGenericCategoryObjectTypeResolver(GenericCategoryObjectTypeResolver $genericCategoryObjectTypeResolver): void
    {
        $this->genericCategoryObjectTypeResolver = $genericCategoryObjectTypeResolver;
    }
    final protected function getGenericCategoryObjectTypeResolver(): GenericCategoryObjectTypeResolver
    {
        /** @var GenericCategoryObjectTypeResolver */
        return $this->genericCategoryObjectTypeResolver ??= $this->instanceManager->getInstance(GenericCategoryObjectTypeResolver::class);
    }
    final public function setPostTagObjectTypeResolver(PostTagObjectTypeResolver $postTagObjectTypeResolver): void
    {
        $this->postTagObjectTypeResolver = $postTagObjectTypeResolver;
    }
    final protected function getPostTagObjectTypeResolver(): PostTagObjectTypeResolver
    {
        /** @var PostTagObjectTypeResolver */
        return $this->postTagObjectTypeResolver ??= $this->instanceManager->getInstance(PostTagObjectTypeResolver::class);
    }
    final public function setPostCategoryObjectTypeResolver(PostCategoryObjectTypeResolver $postCategoryObjectTypeResolver): void
    {
        $this->postCategoryObjectTypeResolver = $postCategoryObjectTypeResolver;
    }
    final protected function getPostCategoryObjectTypeResolver(): PostCategoryObjectTypeResolver
    {
        /** @var PostCategoryObjectTypeResolver */
        return $this->postCategoryObjectTypeResolver ??= $this->instanceManager->getInstance(PostCategoryObjectTypeResolver::class);
    }
    final public function setMenuObjectTypeResolver(MenuObjectTypeResolver $menuObjectTypeResolver): void
    {
        $this->menuObjectTypeResolver = $menuObjectTypeResolver;
    }
    final protected function getMenuObjectTypeResolver(): MenuObjectTypeResolver
    {
        /** @var MenuObjectTypeResolver */
        return $this->menuObjectTypeResolver ??= $this->instanceManager->getInstance(MenuObjectTypeResolver::class);
    }
    final public function setPostObjectTypeResolver(PostObjectTypeResolver $postObjectTypeResolver): void
    {
        $this->postObjectTypeResolver = $postObjectTypeResolver;
    }
    final protected function getPostObjectTypeResolver(): PostObjectTypeResolver
    {
        /** @var PostObjectTypeResolver */
        return $this->postObjectTypeResolver ??= $this->instanceManager->getInstance(PostObjectTypeResolver::class);
    }
    final public function setUserRoleObjectTypeResolver(UserRoleObjectTypeResolver $userRoleObjectTypeResolver): void
    {
        $this->userRoleObjectTypeResolver = $userRoleObjectTypeResolver;
    }
    final protected function getUserRoleObjectTypeResolver(): UserRoleObjectTypeResolver
    {
        /** @var UserRoleObjectTypeResolver */
        return $this->userRoleObjectTypeResolver ??= $this->instanceManager->getInstance(UserRoleObjectTypeResolver::class);
    }
    final public function setUserAvatarObjectTypeResolver(UserAvatarObjectTypeResolver $userAvatarObjectTypeResolver): void
    {
        $this->userAvatarObjectTypeResolver = $userAvatarObjectTypeResolver;
    }
    final protected function getUserAvatarObjectTypeResolver(): UserAvatarObjectTypeResolver
    {
        /** @var UserAvatarObjectTypeResolver */
        return $this->userAvatarObjectTypeResolver ??= $this->instanceManager->getInstance(UserAvatarObjectTypeResolver::class);
    }
    final public function setUserObjectTypeResolver(UserObjectTypeResolver $userObjectTypeResolver): void
    {
        $this->userObjectTypeResolver = $userObjectTypeResolver;
    }
    final protected function getUserObjectTypeResolver(): UserObjectTypeResolver
    {
        /** @var UserObjectTypeResolver */
        return $this->userObjectTypeResolver ??= $this->instanceManager->getInstance(UserObjectTypeResolver::class);
    }
    final public function setWPDataModelProvider(WPDataModelProviderInterface $wpDataModelProvider): void
    {
        $this->wpDataModelProvider = $wpDataModelProvider;
    }
    final protected function getWPDataModelProvider(): WPDataModelProviderInterface
    {
        /** @var WPDataModelProviderInterface */
        return $this->wpDataModelProvider ??= $this->instanceManager->getInstance(WPDataModelProviderInterface::class);
    }
    final public function setMarkdownContentParser(MarkdownContentParserInterface $markdownContentParser): void
    {
        $this->markdownContentParser = $markdownContentParser;
    }
    final protected function getMarkdownContentParser(): MarkdownContentParserInterface
    {
        /** @var MarkdownContentParserInterface */
        return $this->markdownContentParser ??= $this->instanceManager->getInstance(MarkdownContentParserInterface::class);
    }

    /**
     * @return string[]
     */
    public function getModulesToResolve(): array
    {
        return [
            self::SCHEMA_CUSTOMPOSTS,
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
     * @return array<string[]> List of entries that must be satisfied, each entry is an array where at least 1 module must be satisfied
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
            self::SCHEMA_POSTS => \__('Posts', 'gato-graphql'),
            self::SCHEMA_COMMENTS => \__('Comments', 'gato-graphql'),
            self::SCHEMA_USERS => \__('Users', 'gato-graphql'),
            self::SCHEMA_USER_ROLES => \__('User Roles', 'gato-graphql'),
            self::SCHEMA_USER_AVATARS => \__('User Avatars', 'gato-graphql'),
            self::SCHEMA_PAGES => \__('Pages', 'gato-graphql'),
            self::SCHEMA_MEDIA => \__('Media', 'gato-graphql'),
            self::SCHEMA_TAGS => \__('Tags', 'gato-graphql'),
            self::SCHEMA_POST_TAGS => \__('Post Tags', 'gato-graphql'),
            self::SCHEMA_CATEGORIES => \__('Categories', 'gato-graphql'),
            self::SCHEMA_POST_CATEGORIES => \__('Post Categories', 'gato-graphql'),
            self::SCHEMA_MENUS => \__('Menus', 'gato-graphql'),
            self::SCHEMA_SETTINGS => \__('Settings', 'gato-graphql'),
            self::SCHEMA_CUSTOMPOSTS => \__('Custom Posts', 'gato-graphql'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        switch ($module) {
            case self::SCHEMA_POSTS:
                return sprintf(
                    \__('Query %1$s, through type <code>%2$s</code> added to the schema', 'gato-graphql'),
                    \__('posts', 'gato-graphql'),
                    $this->getPostObjectTypeResolver()->getTypeName()
                );
            case self::SCHEMA_USERS:
                return sprintf(
                    \__('Query %1$s, through type <code>%2$s</code> added to the schema', 'gato-graphql'),
                    \__('users', 'gato-graphql'),
                    $this->getUserObjectTypeResolver()->getTypeName()
                );
            case self::SCHEMA_USER_ROLES:
                return sprintf(
                    \__('Query %1$s, through type <code>%2$s</code> added to the schema', 'gato-graphql'),
                    \__('user roles', 'gato-graphql'),
                    $this->getUserRoleObjectTypeResolver()->getTypeName()
                );
            case self::SCHEMA_USER_AVATARS:
                return sprintf(
                    \__('Query %1$s, through type <code>%2$s</code> added to the schema', 'gato-graphql'),
                    \__('user avatars', 'gato-graphql'),
                    $this->getUserAvatarObjectTypeResolver()->getTypeName()
                );
            case self::SCHEMA_PAGES:
                return sprintf(
                    \__('Query %1$s, through type <code>%2$s</code> added to the schema', 'gato-graphql'),
                    \__('pages', 'gato-graphql'),
                    $this->getPageObjectTypeResolver()->getTypeName()
                );
            case self::SCHEMA_MEDIA:
                return sprintf(
                    \__('Query %1$s, through type <code>%2$s</code> added to the schema', 'gato-graphql'),
                    \__('media elements', 'gato-graphql'),
                    $this->getMediaObjectTypeResolver()->getTypeName()
                );
            case self::SCHEMA_COMMENTS:
                return sprintf(
                    \__('Query %1$s, through type <code>%2$s</code> added to the schema', 'gato-graphql'),
                    \__('comments', 'gato-graphql'),
                    $this->getCommentObjectTypeResolver()->getTypeName()
                );
            case self::SCHEMA_POST_TAGS:
                return sprintf(
                    \__('Query %1$s, through type <code>%2$s</code> added to the schema', 'gato-graphql'),
                    \__('post tags', 'gato-graphql'),
                    $this->getPostTagObjectTypeResolver()->getTypeName()
                );
            case self::SCHEMA_POST_CATEGORIES:
                return sprintf(
                    \__('Query %1$s, through type <code>%2$s</code> added to the schema', 'gato-graphql'),
                    \__('post categories', 'gato-graphql'),
                    $this->getPostCategoryObjectTypeResolver()->getTypeName()
                );
            case self::SCHEMA_MENUS:
                return sprintf(
                    \__('Query %1$s, through type <code>%2$s</code> added to the schema', 'gato-graphql'),
                    \__('menus', 'gato-graphql'),
                    $this->getMenuObjectTypeResolver()->getTypeName()
                );
            case self::SCHEMA_SETTINGS:
                return \__('Fetch settings from the site', 'gato-graphql');
            case self::SCHEMA_CUSTOMPOSTS:
                return \__('Base functionality for all custom posts', 'gato-graphql');
            case self::SCHEMA_TAGS:
                return \__('Base functionality for all tags', 'gato-graphql');
            case self::SCHEMA_CATEGORIES:
                return \__('Base functionality for all categories', 'gato-graphql');
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
            case self::SCHEMA_POST_TAGS:
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
        $useRestrictiveDefaults = BehaviorHelpers::areRestrictiveDefaultsEnabled();

        if (
            $module === self::SCHEMA_CUSTOMPOSTS
            && $option === ModuleSettingOptions::CUSTOMPOST_TYPES
        ) {
            return $useRestrictiveDefaults
                ? ConfigurationDefaultValues::DEFAULT_CUSTOMPOST_TYPES
                : $this->getWPDataModelProvider()->getFilteredNonGatoGraphQLPluginCustomPostTypes();
        }

        if (
            $module === self::SCHEMA_TAGS
            && $option === ModuleSettingOptions::TAG_TAXONOMIES
        ) {
            return $useRestrictiveDefaults
                ? ConfigurationDefaultValues::DEFAULT_TAG_TAXONOMIES
                : $this->getWPDataModelProvider()->getFilteredNonGatoGraphQLPluginTagTaxonomies();
        }

        if (
            $module === self::SCHEMA_CATEGORIES
            && $option === ModuleSettingOptions::CATEGORY_TAXONOMIES
        ) {
            return $useRestrictiveDefaults
                ? ConfigurationDefaultValues::DEFAULT_CATEGORY_TAXONOMIES
                : $this->getWPDataModelProvider()->getFilteredNonGatoGraphQLPluginCategoryTaxonomies();
        }

        $defaultValues = [
            self::SCHEMA_CUSTOMPOSTS => [
                ModuleSettingOptions::LIST_DEFAULT_LIMIT => 10,
                ModuleSettingOptions::LIST_MAX_LIMIT => $useRestrictiveDefaults ? 100 : -1,
                self::OPTION_USE_SINGLE_TYPE_INSTEAD_OF_UNION_TYPE => false,
                self::OPTION_TREAT_CUSTOMPOST_STATUS_AS_SENSITIVE_DATA => true,
            ],
            self::SCHEMA_POSTS => [
                ModuleSettingOptions::LIST_DEFAULT_LIMIT => 10,
                ModuleSettingOptions::LIST_MAX_LIMIT => $useRestrictiveDefaults ? 100 : -1,
            ],
            self::SCHEMA_PAGES => [
                ModuleSettingOptions::LIST_DEFAULT_LIMIT => 10,
                ModuleSettingOptions::LIST_MAX_LIMIT => $useRestrictiveDefaults ? 100 : -1,
            ],
            self::SCHEMA_USERS => [
                ModuleSettingOptions::LIST_DEFAULT_LIMIT => 10,
                ModuleSettingOptions::LIST_MAX_LIMIT => $useRestrictiveDefaults ? 100 : -1,
                self::OPTION_TREAT_USER_EMAIL_AS_SENSITIVE_DATA => true,
            ],
            self::SCHEMA_USER_ROLES => [
                self::OPTION_TREAT_USER_ROLE_AS_SENSITIVE_DATA => true,
                self::OPTION_TREAT_USER_CAPABILITY_AS_SENSITIVE_DATA => true,
            ],
            self::SCHEMA_MEDIA => [
                ModuleSettingOptions::LIST_DEFAULT_LIMIT => 10,
                ModuleSettingOptions::LIST_MAX_LIMIT => $useRestrictiveDefaults ? 100 : -1,
            ],
            self::SCHEMA_MENUS => [
                ModuleSettingOptions::LIST_DEFAULT_LIMIT => 10,
                ModuleSettingOptions::LIST_MAX_LIMIT => $useRestrictiveDefaults ? 100 : -1,
            ],
            self::SCHEMA_TAGS => [
                ModuleSettingOptions::LIST_DEFAULT_LIMIT => 10,
                ModuleSettingOptions::LIST_MAX_LIMIT => $useRestrictiveDefaults ? 100 : -1,
            ],
            self::SCHEMA_CATEGORIES => [
                ModuleSettingOptions::LIST_DEFAULT_LIMIT => 10,
                ModuleSettingOptions::LIST_MAX_LIMIT => $useRestrictiveDefaults ? 100 : -1,
            ],
            self::SCHEMA_SETTINGS => [
                ModuleSettingOptions::ENTRIES => $useRestrictiveDefaults ? [
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
                ] : [],
                ModuleSettingOptions::BEHAVIOR => $useRestrictiveDefaults ? Behaviors::ALLOW : Behaviors::DENY,
            ],
            self::SCHEMA_USER_AVATARS => [
                self::OPTION_DEFAULT_AVATAR_SIZE => 96,
            ],
            self::SCHEMA_COMMENTS => [
                self::OPTION_ROOT_COMMENT_LIST_DEFAULT_LIMIT => 10,
                self::OPTION_CUSTOMPOST_COMMENT_OR_COMMENT_RESPONSE_LIST_DEFAULT_LIMIT => -1,
                ModuleSettingOptions::LIST_MAX_LIMIT => -1,
                self::OPTION_TREAT_COMMENT_STATUS_AS_SENSITIVE_DATA => true,
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
        // Common variables to set the limit on the schema types
        $limitArg = 'limit';
        $unlimitedValue = -1;
        $defaultLimitMessagePlaceholder = \__('Number of results from querying %s when argument <code>%s</code> is not provided. Use <code>%s</code> for unlimited.', 'gato-graphql');
        $maxLimitMessagePlaceholder = \__('Maximum number of results from querying %s. Use <code>%s</code> for unlimited.', 'gato-graphql');
        $sensitiveDataTitlePlaceholder = \__('Treat %s as “sensitive” data', 'gato-graphql');
        $sensitiveDataDescPlaceholder = \__('If checked, the <strong>%s</strong> data is exposed in the schema (whether as an object field for querying, or as an input field for filtering) only if the Schema Configuration has option <code>Expose Sensitive Data in the Schema</code> enabled', 'gato-graphql');
        $taxonomyDescPlaceholder = \__('This list contains all the "%1$shierarchical" taxonomies which are associated to queryable custom posts, i.e. those selected in "Included custom post types" in the Settings for "Custom Posts". Each %2$s taxonomy\'s associated custom post types is shown under <code>(CPT: ...)</code>. If your desired %2$s taxonomy does not appear here, make sure that all of its associated custom post types are in that allowlist.', 'gato-graphql');
        $defaultValueDesc = $this->getDefaultValueDescription($this->getName($module));
        // Do the if one by one, so that the SELECT do not get evaluated unless needed
        if (
            in_array($module, [
                self::SCHEMA_CUSTOMPOSTS,
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
                self::SCHEMA_CUSTOMPOSTS => \__('custom posts', 'gato-graphql'),
                self::SCHEMA_POSTS => \__('posts', 'gato-graphql'),
                self::SCHEMA_USERS => \__('users', 'gato-graphql'),
                self::SCHEMA_MEDIA => \__('media items', 'gato-graphql'),
                self::SCHEMA_MENUS => \__('menus', 'gato-graphql'),
                self::SCHEMA_TAGS => \__('tags', 'gato-graphql'),
                self::SCHEMA_CATEGORIES => \__('categories', 'gato-graphql'),
                self::SCHEMA_PAGES => \__('pages', 'gato-graphql'),
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
                    \__('Default limit for %s', 'gato-graphql'),
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
                    \__('Max limit for %s', 'gato-graphql'),
                    $entities
                ),
                Properties::DESCRIPTION => sprintf(
                    $maxLimitMessagePlaceholder,
                    $entities,
                    $unlimitedValue,
                ),
                Properties::TYPE => Properties::TYPE_INT,
                Properties::MIN_NUMBER => -1,
            ];

            if ($module === self::SCHEMA_CUSTOMPOSTS) {
                $possibleCustomPostTypes = $this->getWPDataModelProvider()->getFilteredNonGatoGraphQLPluginCustomPostTypes();
                // The possible values must have key and value
                $possibleValues = [];
                foreach ($possibleCustomPostTypes as $value) {
                    $possibleValues[$value] = $value;
                }
                // Set the setting
                $option = ModuleSettingOptions::CUSTOMPOST_TYPES;
                $moduleSettings[] = [
                    Properties::INPUT => $option,
                    Properties::NAME => $this->getSettingOptionName(
                        $module,
                        $option
                    ),
                    Properties::TITLE => \__('Included custom post types', 'gato-graphql'),
                    Properties::DESCRIPTION => sprintf(
                        \__('Select the custom post types that can be queried, to be accessible via <code>%s</code>. A custom post type will be represented by its own type in the schema if available (such as <code>%s</code> or <code>%s</code>) or, otherwise, via <code>%s</code>.<br/>%s<br/>%s', 'gato-graphql'),
                        $this->getCustomPostUnionTypeResolver()->getTypeName(),
                        $this->getPostObjectTypeResolver()->getTypeName(),
                        $this->getPageObjectTypeResolver()->getTypeName(),
                        $this->getGenericCustomPostObjectTypeResolver()->getTypeName(),
                        $this->getPressCtrlToSelectMoreThanOneOptionLabel(),
                        $defaultValueDesc,
                    ),
                    Properties::TYPE => Properties::TYPE_ARRAY,
                    // Fetch all Schema Configurations from the DB
                    Properties::POSSIBLE_VALUES => $possibleValues,
                    Properties::IS_MULTIPLE => true,
                ];

                $option = self::OPTION_USE_SINGLE_TYPE_INSTEAD_OF_UNION_TYPE;
                $moduleSettings[] = [
                    Properties::INPUT => $option,
                    Properties::NAME => $this->getSettingOptionName(
                        $module,
                        $option
                    ),
                    Properties::TITLE => \__('Use single type instead of union type?', 'gato-graphql'),
                    Properties::DESCRIPTION => sprintf(
                        \__('If type <code>%s</code> is composed of only one type (eg: <code>%s</code>), then directly return this single type, instead of the union type?', 'gato-graphql'),
                        $this->getCustomPostUnionTypeResolver()->getTypeName(),
                        $this->getPostObjectTypeResolver()->getTypeName(),
                    ),
                    Properties::TYPE => Properties::TYPE_BOOL,
                ];

                $option = self::OPTION_TREAT_CUSTOMPOST_STATUS_AS_SENSITIVE_DATA;
                $moduleSettings[] = [
                    Properties::INPUT => $option,
                    Properties::NAME => $this->getSettingOptionName(
                        $module,
                        $option
                    ),
                    Properties::TITLE => sprintf(
                        $sensitiveDataTitlePlaceholder,
                        \__('custom post status', 'gato-graphql'),
                    ),
                    Properties::DESCRIPTION => sprintf(
                        $sensitiveDataDescPlaceholder,
                        \__('custom post status', 'gato-graphql'),
                    ),
                    Properties::TYPE => Properties::TYPE_BOOL,
                ];
            } elseif ($module === self::SCHEMA_USERS) {
                $option = self::OPTION_TREAT_USER_EMAIL_AS_SENSITIVE_DATA;
                $moduleSettings[] = [
                    Properties::INPUT => $option,
                    Properties::NAME => $this->getSettingOptionName(
                        $module,
                        $option
                    ),
                    Properties::TITLE => sprintf(
                        $sensitiveDataTitlePlaceholder,
                        \__('user email', 'gato-graphql'),
                    ),
                    Properties::DESCRIPTION => sprintf(
                        $sensitiveDataDescPlaceholder,
                        \__('user email', 'gato-graphql'),
                    ),
                    Properties::TYPE => Properties::TYPE_BOOL,
                ];
            } elseif ($module === self::SCHEMA_TAGS) {
                $possibleTagTaxonomies = $this->getWPDataModelProvider()->getFilteredNonGatoGraphQLPluginTagTaxonomies();
                $queryableTagTaxonomyNameObjects = $this->getWPDataModelProvider()->getQueryableCustomPostsAssociatedTaxonomies(false);

                // The possible values must have key and value
                $possibleValues = [];
                foreach ($possibleTagTaxonomies as $tagTaxonomyName) {
                    $tagTaxonomyObject = $queryableTagTaxonomyNameObjects[$tagTaxonomyName];
                    $possibleValues[$tagTaxonomyName] = sprintf(
                        $this->__('%s (CPT: "%s")', 'gato-graphql'),
                        $tagTaxonomyName,
                        implode(
                            $this->__('", "', 'gato-graphql'),
                            $tagTaxonomyObject->object_type
                        )
                    );
                }
                // Set the setting
                $option = ModuleSettingOptions::TAG_TAXONOMIES;
                $moduleSettings[] = [
                    Properties::INPUT => $option,
                    Properties::NAME => $this->getSettingOptionName(
                        $module,
                        $option
                    ),
                    Properties::TITLE => \__('Included tag taxonomies', 'gato-graphql'),
                    Properties::DESCRIPTION => sprintf(
                        '%s<br/><br/>%s',
                        sprintf(
                            $taxonomyDescPlaceholder,
                            \__('non-', 'gato-graphql'),
                            \__('tag', 'gato-graphql'),
                        ),
                        sprintf(
                            \__('Select the tag taxonomies that can be queried, to be accessible via <code>%s</code>. A tag taxonomy will be represented by its own type in the schema if available (such as <code>%s</code>) or, otherwise, via <code>%s</code>.<br/>%s<br/>%s', 'gato-graphql'),
                            $this->getTagUnionTypeResolver()->getTypeName(),
                            $this->getPostTagObjectTypeResolver()->getTypeName(),
                            $this->getGenericTagObjectTypeResolver()->getTypeName(),
                            $this->getPressCtrlToSelectMoreThanOneOptionLabel(),
                            $defaultValueDesc,
                        )
                    ),
                    Properties::TYPE => Properties::TYPE_ARRAY,
                    // Fetch all Schema Configurations from the DB
                    Properties::POSSIBLE_VALUES => $possibleValues,
                    Properties::IS_MULTIPLE => true,
                ];
            } elseif ($module === self::SCHEMA_CATEGORIES) {
                $possibleCategoryTaxonomies = $this->getWPDataModelProvider()->getFilteredNonGatoGraphQLPluginCategoryTaxonomies();
                $queryableCategoryTaxonomyNameObjects = $this->getWPDataModelProvider()->getQueryableCustomPostsAssociatedTaxonomies(true);

                // The possible values must have key and value
                $possibleValues = [];
                foreach ($possibleCategoryTaxonomies as $categoryTaxonomyName) {
                    $categoryTaxonomyObject = $queryableCategoryTaxonomyNameObjects[$categoryTaxonomyName];
                    $possibleValues[$categoryTaxonomyName] = sprintf(
                        $this->__('%s (CPT: "%s")', 'gato-graphql'),
                        $categoryTaxonomyName,
                        implode(
                            $this->__('", "', 'gato-graphql'),
                            $categoryTaxonomyObject->object_type
                        )
                    );
                }
                // Set the setting
                $option = ModuleSettingOptions::CATEGORY_TAXONOMIES;
                $moduleSettings[] = [
                    Properties::INPUT => $option,
                    Properties::NAME => $this->getSettingOptionName(
                        $module,
                        $option
                    ),
                    Properties::TITLE => \__('Included category taxonomies', 'gato-graphql'),
                    Properties::DESCRIPTION => sprintf(
                        '%s<br/><br/>%s',
                        sprintf(
                            $taxonomyDescPlaceholder,
                            '',
                            \__('category', 'gato-graphql'),
                        ),
                        sprintf(
                            \__('Select the category taxonomies that can be queried, to be accessible via <code>%s</code>. A tag taxonomy will be represented by its own type in the schema if available (such as <code>%s</code>) or, otherwise, via <code>%s</code>.<br/>%s<br/>%s', 'gato-graphql'),
                            $this->getCategoryUnionTypeResolver()->getTypeName(),
                            $this->getPostCategoryObjectTypeResolver()->getTypeName(),
                            $this->getGenericCategoryObjectTypeResolver()->getTypeName(),
                            $this->getPressCtrlToSelectMoreThanOneOptionLabel(),
                            $defaultValueDesc,
                        )
                    ),
                    Properties::TYPE => Properties::TYPE_ARRAY,
                    // Fetch all Schema Configurations from the DB
                    Properties::POSSIBLE_VALUES => $possibleValues,
                    Properties::IS_MULTIPLE => true,
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
                Properties::TITLE => \__('Default limit for querying comments in the Root', 'gato-graphql'),
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
                Properties::TITLE => \__('Default limit for querying comments under a custom post or comment', 'gato-graphql'),
                Properties::DESCRIPTION => sprintf(
                    $defaultLimitMessagePlaceholder,
                    sprintf(
                        \__('%s and %s', 'gato-graphql'),
                        '<code>Commentable.comments</code>',
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
                Properties::TITLE => \__('Max limit for querying comments', 'gato-graphql'),
                Properties::DESCRIPTION => sprintf(
                    $maxLimitMessagePlaceholder,
                    \__('comments', 'gato-graphql'),
                    $unlimitedValue,
                ),
                Properties::TYPE => Properties::TYPE_INT,
                Properties::MIN_NUMBER => -1,
            ];

            $option = self::OPTION_TREAT_COMMENT_STATUS_AS_SENSITIVE_DATA;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => sprintf(
                    $sensitiveDataTitlePlaceholder,
                    \__('comment status', 'gato-graphql'),
                ),
                Properties::DESCRIPTION => sprintf(
                    $sensitiveDataDescPlaceholder,
                    \__('comment status', 'gato-graphql'),
                ),
                Properties::TYPE => Properties::TYPE_BOOL,
            ];
        } elseif (
            in_array($module, [
                self::SCHEMA_SETTINGS,
            ])
        ) {
            $entriesTitle = \__('Settings entries', 'gato-graphql');
            $headsUpDesc = \__('<strong>Heads up:</strong> Entries surrounded with <code>/</code> or <code>#</code> are evaluated as regex (regular expressions).', 'gato-graphql');
            $entryDesc = \__('<strong>Example:</strong> Any of these entries match option name <code>"%1$s"</code>: %2$s', 'gato-graphql');
            $ulPlaceholder = '<ul><li><code>%s</code></li></ul>';
            $moduleDescriptions = [
                self::SCHEMA_SETTINGS => sprintf(
                    \__('%1$s<hr/>%2$s<hr/>%3$s%4$s', 'gato-graphql'),
                    sprintf(
                        \__('List of all the option names, to either allow or deny access to, when querying fields <code>%s</code>, <code>%s</code> and <code>%s</code> (one entry per line).', 'gato-graphql'),
                        'optionValue',
                        'optionValues',
                        'optionObjectValue'
                    ),
                    $headsUpDesc,
                    sprintf(
                        $entryDesc,
                        'siteurl',
                        sprintf(
                            $ulPlaceholder,
                            implode(
                                '</code></li><li><code>',
                                [
                                    'siteurl',
                                    '/site.*/',
                                    '#site([a-zA-Z]*)#',
                                ]
                            )
                        )
                    ),
                    $defaultValueDesc,
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
                Properties::TITLE => \__('Behavior', 'gato-graphql'),
                Properties::DESCRIPTION => sprintf(
                    '%s %s%s',
                    \__('Are the entries being allowed or denied access to?', 'gato-graphql'),
                    \__('<ul><li>Allow access: only the configured entries can be accessed, and no other can.</li><li>Deny access: the configured entries cannot be accessed, all other entries can.</li></ul>', 'gato-graphql'),
                    $defaultValueDesc,
                ),
                Properties::TYPE => Properties::TYPE_STRING,
                Properties::POSSIBLE_VALUES => [
                    Behaviors::ALLOW => \__('Allow access', 'gato-graphql'),
                    Behaviors::DENY => \__('Deny access', 'gato-graphql'),
                ],
            ];
        } elseif ($module === self::SCHEMA_USER_AVATARS) {
            $option = self::OPTION_DEFAULT_AVATAR_SIZE;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Default avatar size', 'gato-graphql'),
                Properties::DESCRIPTION => sprintf(
                    \__('Size of the avatar (in pixels) when not providing argument <code>"size"</code> in field <code>%s.avatar</code>', 'gato-graphql'),
                    $this->getUserObjectTypeResolver()->getTypeName()
                ),
                Properties::TYPE => Properties::TYPE_INT,
                Properties::MIN_NUMBER => 1,
            ];
        } elseif ($module === self::SCHEMA_USER_ROLES) {
            $option = self::OPTION_TREAT_USER_ROLE_AS_SENSITIVE_DATA;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => sprintf(
                    $sensitiveDataTitlePlaceholder,
                    \__('user roles', 'gato-graphql'),
                ),
                Properties::DESCRIPTION => sprintf(
                    $sensitiveDataDescPlaceholder,
                    \__('user roles', 'gato-graphql'),
                ),
                Properties::TYPE => Properties::TYPE_BOOL,
            ];

            $option = self::OPTION_TREAT_USER_CAPABILITY_AS_SENSITIVE_DATA;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => sprintf(
                    $sensitiveDataTitlePlaceholder,
                    \__('user capabilities', 'gato-graphql'),
                ),
                Properties::DESCRIPTION => sprintf(
                    $sensitiveDataDescPlaceholder,
                    \__('user capabilities', 'gato-graphql'),
                ),
                Properties::TYPE => Properties::TYPE_BOOL,
            ];
        }

        return $moduleSettings;
    }
}
