<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\Constants\ConfigurationDefaultValues;
use GatoGraphQL\GatoGraphQL\Constants\ModuleSettingOptions;
use GatoGraphQL\GatoGraphQL\ContentProcessors\MarkdownContentParserInterface;
use GatoGraphQL\GatoGraphQL\ModuleSettings\Properties;
use GatoGraphQL\GatoGraphQL\ObjectModels\DependedOnInactiveWordPressPlugin;
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
use PoPWPSchema\Blocks\TypeResolvers\ObjectType\GeneralBlockObjectTypeResolver;
use PoPWPSchema\Blocks\TypeResolvers\UnionType\BlockUnionTypeResolver;

use function is_multisite;

class SchemaTypeModuleResolver extends AbstractModuleResolver
{
    use ModuleResolverTrait {
        ModuleResolverTrait::hasDocumentation as upstreamHasDocumentation;
    }
    use SchemaTypeModuleResolverTrait;

    public final const SCHEMA_CUSTOMPOSTS = Plugin::NAMESPACE . '\schema-customposts';
    public final const SCHEMA_POSTS = Plugin::NAMESPACE . '\schema-posts';
    public final const SCHEMA_BLOCKS = Plugin::NAMESPACE . '\schema-blocks';
    public final const SCHEMA_COMMENTS = Plugin::NAMESPACE . '\schema-comments';
    public final const SCHEMA_USERS = Plugin::NAMESPACE . '\schema-users';
    public final const SCHEMA_USER_ROLES = Plugin::NAMESPACE . '\schema-user-roles';
    public final const SCHEMA_USER_AVATARS = Plugin::NAMESPACE . '\schema-user-avatars';
    public final const SCHEMA_PAGES = Plugin::NAMESPACE . '\schema-pages';
    public final const SCHEMA_MEDIA = Plugin::NAMESPACE . '\schema-media';
    public final const SCHEMA_SITE = Plugin::NAMESPACE . '\schema-site';
    public final const SCHEMA_MULTISITE = Plugin::NAMESPACE . '\schema-multisite';
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
    public final const OPTION_TREAT_CUSTOMPOST_STATUS_AS_SENSITIVE_DATA = 'treat-custompost-status-as-sensitive-data';
    public final const OPTION_TREAT_CUSTOMPOST_RAW_CONTENT_FIELDS_AS_SENSITIVE_DATA = 'treat-custompost-raw-content-fields-as-sensitive-data';
    public final const OPTION_TREAT_CUSTOMPOST_EDIT_URL_AS_SENSITIVE_DATA = 'treat-custompost-edit-url-as-sensitive-data';
    public final const OPTION_TREAT_COMMENT_STATUS_AS_SENSITIVE_DATA = 'treat-comment-status-as-sensitive-data';
    public final const OPTION_TREAT_COMMENT_RAW_CONTENT_AS_SENSITIVE_DATA = 'treat-comment-raw-content-as-sensitive-data';
    public final const OPTION_TREAT_USER_EMAIL_AS_SENSITIVE_DATA = 'treat-user-email-as-sensitive-data';
    public final const OPTION_TREAT_USER_ROLE_AS_SENSITIVE_DATA = 'treat-user-role-as-sensitive-data';
    public final const OPTION_TREAT_USER_CAPABILITY_AS_SENSITIVE_DATA = 'treat-user-capability-as-sensitive-data';
    public final const OPTION_TREAT_MENUITEM_RAW_TITLE_AS_SENSITIVE_DATA = 'treat-menuitem-raw-title-as-sensitive-data';

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
    private ?BlockUnionTypeResolver $blockUnionTypeResolver = null;
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
    private ?GeneralBlockObjectTypeResolver $generalBlockObjectTypeResolver = null;
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
        if ($this->commentObjectTypeResolver === null) {
            /** @var CommentObjectTypeResolver */
            $commentObjectTypeResolver = $this->instanceManager->getInstance(CommentObjectTypeResolver::class);
            $this->commentObjectTypeResolver = $commentObjectTypeResolver;
        }
        return $this->commentObjectTypeResolver;
    }
    final public function setCustomPostUnionTypeResolver(CustomPostUnionTypeResolver $customPostUnionTypeResolver): void
    {
        $this->customPostUnionTypeResolver = $customPostUnionTypeResolver;
    }
    final protected function getCustomPostUnionTypeResolver(): CustomPostUnionTypeResolver
    {
        if ($this->customPostUnionTypeResolver === null) {
            /** @var CustomPostUnionTypeResolver */
            $customPostUnionTypeResolver = $this->instanceManager->getInstance(CustomPostUnionTypeResolver::class);
            $this->customPostUnionTypeResolver = $customPostUnionTypeResolver;
        }
        return $this->customPostUnionTypeResolver;
    }
    final public function setBlockUnionTypeResolver(BlockUnionTypeResolver $blockUnionTypeResolver): void
    {
        $this->blockUnionTypeResolver = $blockUnionTypeResolver;
    }
    final protected function getBlockUnionTypeResolver(): BlockUnionTypeResolver
    {
        if ($this->blockUnionTypeResolver === null) {
            /** @var BlockUnionTypeResolver */
            $blockUnionTypeResolver = $this->instanceManager->getInstance(BlockUnionTypeResolver::class);
            $this->blockUnionTypeResolver = $blockUnionTypeResolver;
        }
        return $this->blockUnionTypeResolver;
    }
    final public function setTagUnionTypeResolver(TagUnionTypeResolver $tagUnionTypeResolver): void
    {
        $this->tagUnionTypeResolver = $tagUnionTypeResolver;
    }
    final protected function getTagUnionTypeResolver(): TagUnionTypeResolver
    {
        if ($this->tagUnionTypeResolver === null) {
            /** @var TagUnionTypeResolver */
            $tagUnionTypeResolver = $this->instanceManager->getInstance(TagUnionTypeResolver::class);
            $this->tagUnionTypeResolver = $tagUnionTypeResolver;
        }
        return $this->tagUnionTypeResolver;
    }
    final public function setCategoryUnionTypeResolver(CategoryUnionTypeResolver $categoryUnionTypeResolver): void
    {
        $this->categoryUnionTypeResolver = $categoryUnionTypeResolver;
    }
    final protected function getCategoryUnionTypeResolver(): CategoryUnionTypeResolver
    {
        if ($this->categoryUnionTypeResolver === null) {
            /** @var CategoryUnionTypeResolver */
            $categoryUnionTypeResolver = $this->instanceManager->getInstance(CategoryUnionTypeResolver::class);
            $this->categoryUnionTypeResolver = $categoryUnionTypeResolver;
        }
        return $this->categoryUnionTypeResolver;
    }
    final public function setMediaObjectTypeResolver(MediaObjectTypeResolver $mediaObjectTypeResolver): void
    {
        $this->mediaObjectTypeResolver = $mediaObjectTypeResolver;
    }
    final protected function getMediaObjectTypeResolver(): MediaObjectTypeResolver
    {
        if ($this->mediaObjectTypeResolver === null) {
            /** @var MediaObjectTypeResolver */
            $mediaObjectTypeResolver = $this->instanceManager->getInstance(MediaObjectTypeResolver::class);
            $this->mediaObjectTypeResolver = $mediaObjectTypeResolver;
        }
        return $this->mediaObjectTypeResolver;
    }
    final public function setPageObjectTypeResolver(PageObjectTypeResolver $pageObjectTypeResolver): void
    {
        $this->pageObjectTypeResolver = $pageObjectTypeResolver;
    }
    final protected function getPageObjectTypeResolver(): PageObjectTypeResolver
    {
        if ($this->pageObjectTypeResolver === null) {
            /** @var PageObjectTypeResolver */
            $pageObjectTypeResolver = $this->instanceManager->getInstance(PageObjectTypeResolver::class);
            $this->pageObjectTypeResolver = $pageObjectTypeResolver;
        }
        return $this->pageObjectTypeResolver;
    }
    final public function setGenericCustomPostObjectTypeResolver(GenericCustomPostObjectTypeResolver $genericCustomPostObjectTypeResolver): void
    {
        $this->genericCustomPostObjectTypeResolver = $genericCustomPostObjectTypeResolver;
    }
    final protected function getGenericCustomPostObjectTypeResolver(): GenericCustomPostObjectTypeResolver
    {
        if ($this->genericCustomPostObjectTypeResolver === null) {
            /** @var GenericCustomPostObjectTypeResolver */
            $genericCustomPostObjectTypeResolver = $this->instanceManager->getInstance(GenericCustomPostObjectTypeResolver::class);
            $this->genericCustomPostObjectTypeResolver = $genericCustomPostObjectTypeResolver;
        }
        return $this->genericCustomPostObjectTypeResolver;
    }
    final public function setGenericTagObjectTypeResolver(GenericTagObjectTypeResolver $genericTagObjectTypeResolver): void
    {
        $this->genericTagObjectTypeResolver = $genericTagObjectTypeResolver;
    }
    final protected function getGenericTagObjectTypeResolver(): GenericTagObjectTypeResolver
    {
        if ($this->genericTagObjectTypeResolver === null) {
            /** @var GenericTagObjectTypeResolver */
            $genericTagObjectTypeResolver = $this->instanceManager->getInstance(GenericTagObjectTypeResolver::class);
            $this->genericTagObjectTypeResolver = $genericTagObjectTypeResolver;
        }
        return $this->genericTagObjectTypeResolver;
    }
    final public function setGenericCategoryObjectTypeResolver(GenericCategoryObjectTypeResolver $genericCategoryObjectTypeResolver): void
    {
        $this->genericCategoryObjectTypeResolver = $genericCategoryObjectTypeResolver;
    }
    final protected function getGenericCategoryObjectTypeResolver(): GenericCategoryObjectTypeResolver
    {
        if ($this->genericCategoryObjectTypeResolver === null) {
            /** @var GenericCategoryObjectTypeResolver */
            $genericCategoryObjectTypeResolver = $this->instanceManager->getInstance(GenericCategoryObjectTypeResolver::class);
            $this->genericCategoryObjectTypeResolver = $genericCategoryObjectTypeResolver;
        }
        return $this->genericCategoryObjectTypeResolver;
    }
    final public function setPostTagObjectTypeResolver(PostTagObjectTypeResolver $postTagObjectTypeResolver): void
    {
        $this->postTagObjectTypeResolver = $postTagObjectTypeResolver;
    }
    final protected function getPostTagObjectTypeResolver(): PostTagObjectTypeResolver
    {
        if ($this->postTagObjectTypeResolver === null) {
            /** @var PostTagObjectTypeResolver */
            $postTagObjectTypeResolver = $this->instanceManager->getInstance(PostTagObjectTypeResolver::class);
            $this->postTagObjectTypeResolver = $postTagObjectTypeResolver;
        }
        return $this->postTagObjectTypeResolver;
    }
    final public function setPostCategoryObjectTypeResolver(PostCategoryObjectTypeResolver $postCategoryObjectTypeResolver): void
    {
        $this->postCategoryObjectTypeResolver = $postCategoryObjectTypeResolver;
    }
    final protected function getPostCategoryObjectTypeResolver(): PostCategoryObjectTypeResolver
    {
        if ($this->postCategoryObjectTypeResolver === null) {
            /** @var PostCategoryObjectTypeResolver */
            $postCategoryObjectTypeResolver = $this->instanceManager->getInstance(PostCategoryObjectTypeResolver::class);
            $this->postCategoryObjectTypeResolver = $postCategoryObjectTypeResolver;
        }
        return $this->postCategoryObjectTypeResolver;
    }
    final public function setMenuObjectTypeResolver(MenuObjectTypeResolver $menuObjectTypeResolver): void
    {
        $this->menuObjectTypeResolver = $menuObjectTypeResolver;
    }
    final protected function getMenuObjectTypeResolver(): MenuObjectTypeResolver
    {
        if ($this->menuObjectTypeResolver === null) {
            /** @var MenuObjectTypeResolver */
            $menuObjectTypeResolver = $this->instanceManager->getInstance(MenuObjectTypeResolver::class);
            $this->menuObjectTypeResolver = $menuObjectTypeResolver;
        }
        return $this->menuObjectTypeResolver;
    }
    final public function setPostObjectTypeResolver(PostObjectTypeResolver $postObjectTypeResolver): void
    {
        $this->postObjectTypeResolver = $postObjectTypeResolver;
    }
    final protected function getPostObjectTypeResolver(): PostObjectTypeResolver
    {
        if ($this->postObjectTypeResolver === null) {
            /** @var PostObjectTypeResolver */
            $postObjectTypeResolver = $this->instanceManager->getInstance(PostObjectTypeResolver::class);
            $this->postObjectTypeResolver = $postObjectTypeResolver;
        }
        return $this->postObjectTypeResolver;
    }
    final public function setGeneralBlockObjectTypeResolver(GeneralBlockObjectTypeResolver $generalBlockObjectTypeResolver): void
    {
        $this->generalBlockObjectTypeResolver = $generalBlockObjectTypeResolver;
    }
    final protected function getGeneralBlockObjectTypeResolver(): GeneralBlockObjectTypeResolver
    {
        if ($this->generalBlockObjectTypeResolver === null) {
            /** @var GeneralBlockObjectTypeResolver */
            $generalBlockObjectTypeResolver = $this->instanceManager->getInstance(GeneralBlockObjectTypeResolver::class);
            $this->generalBlockObjectTypeResolver = $generalBlockObjectTypeResolver;
        }
        return $this->generalBlockObjectTypeResolver;
    }
    final public function setUserRoleObjectTypeResolver(UserRoleObjectTypeResolver $userRoleObjectTypeResolver): void
    {
        $this->userRoleObjectTypeResolver = $userRoleObjectTypeResolver;
    }
    final protected function getUserRoleObjectTypeResolver(): UserRoleObjectTypeResolver
    {
        if ($this->userRoleObjectTypeResolver === null) {
            /** @var UserRoleObjectTypeResolver */
            $userRoleObjectTypeResolver = $this->instanceManager->getInstance(UserRoleObjectTypeResolver::class);
            $this->userRoleObjectTypeResolver = $userRoleObjectTypeResolver;
        }
        return $this->userRoleObjectTypeResolver;
    }
    final public function setUserAvatarObjectTypeResolver(UserAvatarObjectTypeResolver $userAvatarObjectTypeResolver): void
    {
        $this->userAvatarObjectTypeResolver = $userAvatarObjectTypeResolver;
    }
    final protected function getUserAvatarObjectTypeResolver(): UserAvatarObjectTypeResolver
    {
        if ($this->userAvatarObjectTypeResolver === null) {
            /** @var UserAvatarObjectTypeResolver */
            $userAvatarObjectTypeResolver = $this->instanceManager->getInstance(UserAvatarObjectTypeResolver::class);
            $this->userAvatarObjectTypeResolver = $userAvatarObjectTypeResolver;
        }
        return $this->userAvatarObjectTypeResolver;
    }
    final public function setUserObjectTypeResolver(UserObjectTypeResolver $userObjectTypeResolver): void
    {
        $this->userObjectTypeResolver = $userObjectTypeResolver;
    }
    final protected function getUserObjectTypeResolver(): UserObjectTypeResolver
    {
        if ($this->userObjectTypeResolver === null) {
            /** @var UserObjectTypeResolver */
            $userObjectTypeResolver = $this->instanceManager->getInstance(UserObjectTypeResolver::class);
            $this->userObjectTypeResolver = $userObjectTypeResolver;
        }
        return $this->userObjectTypeResolver;
    }
    final public function setWPDataModelProvider(WPDataModelProviderInterface $wpDataModelProvider): void
    {
        $this->wpDataModelProvider = $wpDataModelProvider;
    }
    final protected function getWPDataModelProvider(): WPDataModelProviderInterface
    {
        if ($this->wpDataModelProvider === null) {
            /** @var WPDataModelProviderInterface */
            $wpDataModelProvider = $this->instanceManager->getInstance(WPDataModelProviderInterface::class);
            $this->wpDataModelProvider = $wpDataModelProvider;
        }
        return $this->wpDataModelProvider;
    }
    final public function setMarkdownContentParser(MarkdownContentParserInterface $markdownContentParser): void
    {
        $this->markdownContentParser = $markdownContentParser;
    }
    final protected function getMarkdownContentParser(): MarkdownContentParserInterface
    {
        if ($this->markdownContentParser === null) {
            /** @var MarkdownContentParserInterface */
            $markdownContentParser = $this->instanceManager->getInstance(MarkdownContentParserInterface::class);
            $this->markdownContentParser = $markdownContentParser;
        }
        return $this->markdownContentParser;
    }

    /**
     * @return string[]
     */
    public function getModulesToResolve(): array
    {
        return [
            self::SCHEMA_CUSTOMPOSTS,
            self::SCHEMA_POSTS,
            self::SCHEMA_BLOCKS,
            self::SCHEMA_PAGES,
            self::SCHEMA_USERS,
            self::SCHEMA_USER_ROLES,
            self::SCHEMA_USER_AVATARS,
            self::SCHEMA_COMMENTS,
            self::SCHEMA_SITE,
            self::SCHEMA_MULTISITE,
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
            case self::SCHEMA_BLOCKS:
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
            self::SCHEMA_POSTS => \__('Posts', 'gatographql'),
            self::SCHEMA_BLOCKS => \__('Blocks', 'gatographql'),
            self::SCHEMA_COMMENTS => \__('Comments', 'gatographql'),
            self::SCHEMA_USERS => \__('Users', 'gatographql'),
            self::SCHEMA_USER_ROLES => \__('User Roles', 'gatographql'),
            self::SCHEMA_USER_AVATARS => \__('User Avatars', 'gatographql'),
            self::SCHEMA_PAGES => \__('Pages', 'gatographql'),
            self::SCHEMA_MEDIA => \__('Media', 'gatographql'),
            self::SCHEMA_SITE => \__('Site', 'gatographql'),
            self::SCHEMA_MULTISITE => \__('Multisite', 'gatographql'),
            self::SCHEMA_TAGS => \__('Tags', 'gatographql'),
            self::SCHEMA_POST_TAGS => \__('Post Tags', 'gatographql'),
            self::SCHEMA_CATEGORIES => \__('Categories', 'gatographql'),
            self::SCHEMA_POST_CATEGORIES => \__('Post Categories', 'gatographql'),
            self::SCHEMA_MENUS => \__('Menus', 'gatographql'),
            self::SCHEMA_SETTINGS => \__('Settings', 'gatographql'),
            self::SCHEMA_CUSTOMPOSTS => \__('Custom Posts', 'gatographql'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        return match ($module) {
            self::SCHEMA_POSTS => sprintf(
                \__('Query %1$s, through type <code>%2$s</code> added to the schema', 'gatographql'),
                \__('posts', 'gatographql'),
                $this->getPostObjectTypeResolver()->getTypeName()
            ),
            self::SCHEMA_BLOCKS => \__('Retrieve the (Gutenberg) blocks contained in the custom post', 'gatographql'),
            self::SCHEMA_USERS => sprintf(
                \__('Query %1$s, through type <code>%2$s</code> added to the schema', 'gatographql'),
                \__('users', 'gatographql'),
                $this->getUserObjectTypeResolver()->getTypeName()
            ),
            self::SCHEMA_USER_ROLES => sprintf(
                \__('Query %1$s, through type <code>%2$s</code> added to the schema', 'gatographql'),
                \__('user roles', 'gatographql'),
                $this->getUserRoleObjectTypeResolver()->getTypeName()
            ),
            self::SCHEMA_USER_AVATARS => sprintf(
                \__('Query %1$s, through type <code>%2$s</code> added to the schema', 'gatographql'),
                \__('user avatars', 'gatographql'),
                $this->getUserAvatarObjectTypeResolver()->getTypeName()
            ),
            self::SCHEMA_PAGES => sprintf(
                \__('Query %1$s, through type <code>%2$s</code> added to the schema', 'gatographql'),
                \__('pages', 'gatographql'),
                $this->getPageObjectTypeResolver()->getTypeName()
            ),
            self::SCHEMA_MEDIA => sprintf(
                \__('Query %1$s, through type <code>%2$s</code> added to the schema', 'gatographql'),
                \__('media elements', 'gatographql'),
                $this->getMediaObjectTypeResolver()->getTypeName()
            ),
            self::SCHEMA_COMMENTS => sprintf(
                \__('Query %1$s, through type <code>%2$s</code> added to the schema', 'gatographql'),
                \__('comments', 'gatographql'),
                $this->getCommentObjectTypeResolver()->getTypeName()
            ),
            self::SCHEMA_POST_TAGS => sprintf(
                \__('Query %1$s, through type <code>%2$s</code> added to the schema', 'gatographql'),
                \__('post tags', 'gatographql'),
                $this->getPostTagObjectTypeResolver()->getTypeName()
            ),
            self::SCHEMA_POST_CATEGORIES => sprintf(
                \__('Query %1$s, through type <code>%2$s</code> added to the schema', 'gatographql'),
                \__('post categories', 'gatographql'),
                $this->getPostCategoryObjectTypeResolver()->getTypeName()
            ),
            self::SCHEMA_MENUS => sprintf(
                \__('Query %1$s, through type <code>%2$s</code> added to the schema', 'gatographql'),
                \__('menus', 'gatographql'),
                $this->getMenuObjectTypeResolver()->getTypeName()
            ),
            self::SCHEMA_SETTINGS => \__('Fetch settings from the site', 'gatographql'),
            self::SCHEMA_CUSTOMPOSTS => \__('Base functionality for all custom posts', 'gatographql'),
            self::SCHEMA_SITE => \__('Fetch site information', 'gatographql'),
            self::SCHEMA_MULTISITE => \__('Fetch site information in a WordPress multisite network', 'gatographql'),
            self::SCHEMA_TAGS => \__('Base functionality for all tags', 'gatographql'),
            self::SCHEMA_CATEGORIES => \__('Base functionality for all categories', 'gatographql'),
            default => parent::getDescription($module),
        };
    }

    /**
     * Disable the Blocks module if "Classic Editor" plugin is installed
     *
     * @return DependedOnInactiveWordPressPlugin[]
     */
    public function getDependentOnInactiveWordPressPlugins(string $module): array
    {
        return match ($module) {
            self::SCHEMA_BLOCKS => [
                new DependedOnInactiveWordPressPlugin(
                    \__('Classic Editor', 'gatographql'),
                    'classic-editor/classic-editor.php',
                ),
            ],
            default => parent::getDependentOnInactiveWordPressPlugins($module),
        };
    }

    public function areRequirementsSatisfied(string $module): bool
    {
        switch ($module) {
            case self::SCHEMA_MULTISITE:
                return is_multisite();
        }
        return parent::areRequirementsSatisfied($module);
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
            case self::SCHEMA_SITE:
            case self::SCHEMA_MULTISITE:
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

        /**
         * When resetting the Settings, the associated tags/categories
         * must be applied on the new set of CPTs, and not on the values
         * stored on the DB
         */
        $defaultQueryableCustomPostTypes = [];
        if (
            !$useRestrictiveDefaults
            && ((
                $module === self::SCHEMA_CUSTOMPOSTS
                && $option === ModuleSettingOptions::CUSTOMPOST_TYPES
            ) || (
                $module === self::SCHEMA_TAGS
                && $option === ModuleSettingOptions::TAG_TAXONOMIES
            ) || (
                $module === self::SCHEMA_CATEGORIES
                && $option === ModuleSettingOptions::CATEGORY_TAXONOMIES
            )
            )
        ) {
            $defaultQueryableCustomPostTypes = $this->getWPDataModelProvider()->getFilteredNonGatoGraphQLPluginCustomPostTypes([
                'publicly_queryable' => true,
            ]);
        }

        if (
            $module === self::SCHEMA_CUSTOMPOSTS
            && $option === ModuleSettingOptions::CUSTOMPOST_TYPES
        ) {
            return $useRestrictiveDefaults
                ? ConfigurationDefaultValues::DEFAULT_CUSTOMPOST_TYPES
                : $defaultQueryableCustomPostTypes;
        }

        if (
            $module === self::SCHEMA_TAGS
            && $option === ModuleSettingOptions::TAG_TAXONOMIES
        ) {
            return $useRestrictiveDefaults
                ? ConfigurationDefaultValues::DEFAULT_TAG_TAXONOMIES
                : $this->getWPDataModelProvider()->getFilteredNonGatoGraphQLPluginTagTaxonomies($defaultQueryableCustomPostTypes);
        }

        if (
            $module === self::SCHEMA_CATEGORIES
            && $option === ModuleSettingOptions::CATEGORY_TAXONOMIES
        ) {
            return $useRestrictiveDefaults
                ? ConfigurationDefaultValues::DEFAULT_CATEGORY_TAXONOMIES
                : $this->getWPDataModelProvider()->getFilteredNonGatoGraphQLPluginCategoryTaxonomies($defaultQueryableCustomPostTypes);
        }

        $defaultValues = [
            self::SCHEMA_CUSTOMPOSTS => [
                ModuleSettingOptions::LIST_DEFAULT_LIMIT => 10,
                ModuleSettingOptions::LIST_MAX_LIMIT => $useRestrictiveDefaults ? 100 : -1,
                self::OPTION_USE_SINGLE_TYPE_INSTEAD_OF_UNION_TYPE => false,
                self::OPTION_TREAT_CUSTOMPOST_STATUS_AS_SENSITIVE_DATA => true,
                self::OPTION_TREAT_CUSTOMPOST_RAW_CONTENT_FIELDS_AS_SENSITIVE_DATA => true,
                self::OPTION_TREAT_CUSTOMPOST_EDIT_URL_AS_SENSITIVE_DATA => true,
            ],
            self::SCHEMA_POSTS => [
                ModuleSettingOptions::LIST_DEFAULT_LIMIT => 10,
                ModuleSettingOptions::LIST_MAX_LIMIT => $useRestrictiveDefaults ? 100 : -1,
            ],
            self::SCHEMA_BLOCKS => [
                self::OPTION_USE_SINGLE_TYPE_INSTEAD_OF_UNION_TYPE => false,
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
                self::OPTION_TREAT_MENUITEM_RAW_TITLE_AS_SENSITIVE_DATA => true,
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
                self::OPTION_TREAT_COMMENT_RAW_CONTENT_AS_SENSITIVE_DATA => true,
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
        $defaultLimitMessagePlaceholder = \__('Number of results from querying %s when argument <code>%s</code> is not provided. Use <code>%s</code> for unlimited.', 'gatographql');
        $maxLimitMessagePlaceholder = \__('Maximum number of results from querying %s. Use <code>%s</code> for unlimited.', 'gatographql');
        $sensitiveDataTitlePlaceholder = \__('Treat %s as “sensitive” data', 'gatographql');
        $sensitiveDataDescPlaceholder = \__('If checked, the <strong>%s</strong> data is exposed in the schema only if the Schema Configuration has option <code>Expose Sensitive Data in the Schema</code> enabled', 'gatographql');
        $taxonomyDescPlaceholder = \__('This list contains all the "%1$shierarchical" taxonomies which are associated to queryable custom posts, i.e. those selected in "Included custom post types" in the Settings for "Custom Posts". Each %2$s taxonomy\'s associated custom post types is shown under <code>(CPT: ...)</code>. If your desired %2$s taxonomy does not appear here, make sure that all of its associated custom post types are in that allowlist.', 'gatographql');
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
                self::SCHEMA_CUSTOMPOSTS => \__('custom posts', 'gatographql'),
                self::SCHEMA_POSTS => \__('posts', 'gatographql'),
                self::SCHEMA_USERS => \__('users', 'gatographql'),
                self::SCHEMA_MEDIA => \__('media items', 'gatographql'),
                self::SCHEMA_MENUS => \__('menus', 'gatographql'),
                self::SCHEMA_TAGS => \__('tags', 'gatographql'),
                self::SCHEMA_CATEGORIES => \__('categories', 'gatographql'),
                self::SCHEMA_PAGES => \__('pages', 'gatographql'),
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
                    \__('Default limit for %s', 'gatographql'),
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
                    \__('Max limit for %s', 'gatographql'),
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
                    Properties::TITLE => \__('Included custom post types', 'gatographql'),
                    Properties::DESCRIPTION => sprintf(
                        \__('Select the custom post types that can be queried, to be accessible via <code>%s</code>. A custom post type will be represented by its own type in the schema if available (such as <code>%s</code> or <code>%s</code>) or, otherwise, via <code>%s</code>.<br/>%s<br/>%s', 'gatographql'),
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
                    Properties::TITLE => \__('Use single type instead of union type?', 'gatographql'),
                    Properties::DESCRIPTION => sprintf(
                        \__('If type <code>%s</code> is composed of only one type (eg: <code>%s</code>), then directly return this single type, instead of the union type?', 'gatographql'),
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
                        \__('custom post status', 'gatographql'),
                    ),
                    Properties::DESCRIPTION => sprintf(
                        $sensitiveDataDescPlaceholder,
                        \__('custom post status', 'gatographql'),
                    ),
                    Properties::TYPE => Properties::TYPE_BOOL,
                ];

                $option = self::OPTION_TREAT_CUSTOMPOST_RAW_CONTENT_FIELDS_AS_SENSITIVE_DATA;
                $moduleSettings[] = [
                    Properties::INPUT => $option,
                    Properties::NAME => $this->getSettingOptionName(
                        $module,
                        $option
                    ),
                    Properties::TITLE => sprintf(
                        $sensitiveDataTitlePlaceholder,
                        \__('custom post raw content fields (<code>rawContent</code>, <code>rawTitle</code> and <code>rawExcerpt</code>)', 'gatographql'),
                    ),
                    Properties::DESCRIPTION => sprintf(
                        $sensitiveDataDescPlaceholder,
                        \__('custom post raw content fields (<code>rawContent</code>, <code>rawTitle</code> and <code>rawExcerpt</code>)', 'gatographql'),
                    ),
                    Properties::TYPE => Properties::TYPE_BOOL,
                ];

                $option = self::OPTION_TREAT_CUSTOMPOST_EDIT_URL_AS_SENSITIVE_DATA;
                $moduleSettings[] = [
                    Properties::INPUT => $option,
                    Properties::NAME => $this->getSettingOptionName(
                        $module,
                        $option
                    ),
                    Properties::TITLE => sprintf(
                        $sensitiveDataTitlePlaceholder,
                        \__('custom post edit link', 'gatographql'),
                    ),
                    Properties::DESCRIPTION => sprintf(
                        $sensitiveDataDescPlaceholder,
                        \__('custom post edit link', 'gatographql'),
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
                        \__('user email', 'gatographql'),
                    ),
                    Properties::DESCRIPTION => sprintf(
                        $sensitiveDataDescPlaceholder,
                        \__('user email', 'gatographql'),
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
                        $this->__('%s (CPT: "%s")', 'gatographql'),
                        $tagTaxonomyName,
                        implode(
                            $this->__('", "', 'gatographql'),
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
                    Properties::TITLE => \__('Included tag taxonomies', 'gatographql'),
                    Properties::DESCRIPTION => sprintf(
                        '%s<br/><br/>%s',
                        sprintf(
                            $taxonomyDescPlaceholder,
                            \__('non-', 'gatographql'),
                            \__('tag', 'gatographql'),
                        ),
                        sprintf(
                            \__('Select the tag taxonomies that can be queried, to be accessible via <code>%s</code>. A tag taxonomy will be represented by its own type in the schema if available (such as <code>%s</code>) or, otherwise, via <code>%s</code>.<br/>%s<br/>%s', 'gatographql'),
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
                        $this->__('%s (CPT: "%s")', 'gatographql'),
                        $categoryTaxonomyName,
                        implode(
                            $this->__('", "', 'gatographql'),
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
                    Properties::TITLE => \__('Included category taxonomies', 'gatographql'),
                    Properties::DESCRIPTION => sprintf(
                        '%s<br/><br/>%s',
                        sprintf(
                            $taxonomyDescPlaceholder,
                            '',
                            \__('category', 'gatographql'),
                        ),
                        sprintf(
                            \__('Select the category taxonomies that can be queried, to be accessible via <code>%s</code>. A tag taxonomy will be represented by its own type in the schema if available (such as <code>%s</code>) or, otherwise, via <code>%s</code>.<br/>%s<br/>%s', 'gatographql'),
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
            } elseif ($module === self::SCHEMA_MENUS) {
                $option = self::OPTION_TREAT_MENUITEM_RAW_TITLE_AS_SENSITIVE_DATA;
                $moduleSettings[] = [
                    Properties::INPUT => $option,
                    Properties::NAME => $this->getSettingOptionName(
                        $module,
                        $option
                    ),
                    Properties::TITLE => sprintf(
                        $sensitiveDataTitlePlaceholder,
                        \__('menu item raw title', 'gatographql'),
                    ),
                    Properties::DESCRIPTION => sprintf(
                        $sensitiveDataDescPlaceholder,
                        \__('menu item raw title', 'gatographql'),
                    ),
                    Properties::TYPE => Properties::TYPE_BOOL,
                ];
            }
        } elseif ($module === self::SCHEMA_BLOCKS) {
            $option = self::OPTION_USE_SINGLE_TYPE_INSTEAD_OF_UNION_TYPE;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Use single type instead of union type?', 'gatographql'),
                Properties::DESCRIPTION => sprintf(
                    \__('If type <code>%s</code> is composed of only one type (<code>%s</code>), then directly return this single type, instead of the union type?', 'gatographql'),
                    $this->getBlockUnionTypeResolver()->getTypeName(),
                    $this->getGeneralBlockObjectTypeResolver()->getTypeName(),
                ),
                Properties::TYPE => Properties::TYPE_BOOL,
            ];
        } elseif ($module === self::SCHEMA_COMMENTS) {
            $option = self::OPTION_ROOT_COMMENT_LIST_DEFAULT_LIMIT;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Default limit for querying comments in the Root', 'gatographql'),
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
                Properties::TITLE => \__('Default limit for querying comments under a custom post or comment', 'gatographql'),
                Properties::DESCRIPTION => sprintf(
                    $defaultLimitMessagePlaceholder,
                    sprintf(
                        \__('%s and %s', 'gatographql'),
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
                Properties::TITLE => \__('Max limit for querying comments', 'gatographql'),
                Properties::DESCRIPTION => sprintf(
                    $maxLimitMessagePlaceholder,
                    \__('comments', 'gatographql'),
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
                    \__('comment status', 'gatographql'),
                ),
                Properties::DESCRIPTION => sprintf(
                    $sensitiveDataDescPlaceholder,
                    \__('comment status', 'gatographql'),
                ),
                Properties::TYPE => Properties::TYPE_BOOL,
            ];

            $option = self::OPTION_TREAT_COMMENT_RAW_CONTENT_AS_SENSITIVE_DATA;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => sprintf(
                    $sensitiveDataTitlePlaceholder,
                    \__('comment raw content', 'gatographql'),
                ),
                Properties::DESCRIPTION => sprintf(
                    $sensitiveDataDescPlaceholder,
                    \__('comment raw content', 'gatographql'),
                ),
                Properties::TYPE => Properties::TYPE_BOOL,
            ];
        } elseif ($module === self::SCHEMA_SETTINGS) {
            $entriesTitle = \__('Settings entries', 'gatographql');
            $headsUpDesc = \__('<strong>Heads up:</strong> Entries surrounded with <code>/</code> or <code>#</code> are evaluated as regex (regular expressions).', 'gatographql');
            $entryDesc = \__('<strong>Example:</strong> Any of these entries match option name <code>"%1$s"</code>: %2$s', 'gatographql');
            $ulPlaceholder = '<ul><li><code>%s</code></li></ul>';
            $moduleDescriptions = [
                self::SCHEMA_SETTINGS => sprintf(
                    \__('%1$s<hr/>%2$s<hr/>%3$s%4$s', 'gatographql'),
                    sprintf(
                        \__('List of all the option names, to either allow or deny access to, when querying fields <code>%s</code>, <code>%s</code> and <code>%s</code> (one entry per line).', 'gatographql'),
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
                Properties::TITLE => \__('Behavior', 'gatographql'),
                Properties::DESCRIPTION => sprintf(
                    '%s %s%s',
                    \__('Are the entries being allowed or denied access to?', 'gatographql'),
                    \__('<ul><li>Allow access: only the configured entries can be accessed, and no other can.</li><li>Deny access: the configured entries cannot be accessed, all other entries can.</li></ul>', 'gatographql'),
                    $defaultValueDesc,
                ),
                Properties::TYPE => Properties::TYPE_STRING,
                Properties::POSSIBLE_VALUES => [
                    Behaviors::ALLOW => \__('Allow access', 'gatographql'),
                    Behaviors::DENY => \__('Deny access', 'gatographql'),
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
                Properties::TITLE => \__('Default avatar size', 'gatographql'),
                Properties::DESCRIPTION => sprintf(
                    \__('Size of the avatar (in pixels) when not providing argument <code>"size"</code> in field <code>%s.avatar</code>', 'gatographql'),
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
                    \__('user roles', 'gatographql'),
                ),
                Properties::DESCRIPTION => sprintf(
                    $sensitiveDataDescPlaceholder,
                    \__('user roles', 'gatographql'),
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
                    \__('user capabilities', 'gatographql'),
                ),
                Properties::DESCRIPTION => sprintf(
                    $sensitiveDataDescPlaceholder,
                    \__('user capabilities', 'gatographql'),
                ),
                Properties::TYPE => Properties::TYPE_BOOL,
            ];
        }

        return $moduleSettings;
    }
}
