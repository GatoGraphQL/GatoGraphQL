<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Container\CompilerPasses;

use GraphQLByPoP\GraphQLServer\Component;
class ConfigureSchemaNamespacingCompilerPass extends AbstractConfigureSchemaNamespacingCompilerPass
{
    /**
     * The entities from the WordPress data model (Post, User, Comment, etc)
     * are considered the canonical source, so they do not need to be namespaced.
     */
    protected function getSchemaNamespace(): string
    {
        return ''; // Empty namespace
    }

    /**
     * @return string[]
     */
    protected function getComponentClasses(): array
    {
        return [
            Component::class,
            \PoP\ComponentModel\Component::class,
            \PoP\Engine\Component::class,
            \PoPCMSSchema\Categories\Component::class,
            \PoPCMSSchema\CommentMeta\Component::class,
            \PoPCMSSchema\CommentMutations\Component::class,
            \PoPCMSSchema\Comments\Component::class,
            \PoPSchema\SchemaCommons\Component::class,
            \PoPCMSSchema\SchemaCommonsWP\Component::class,
            \PoPCMSSchema\CustomPostMedia\Component::class,
            \PoPCMSSchema\CustomPostMedia\Component::class,
            \PoPCMSSchema\CustomPostMediaMutations\Component::class,
            \PoPCMSSchema\PostMediaMutations\Component::class,
            \PoPCMSSchema\CustomPostMeta\Component::class,
            \PoPCMSSchema\CustomPosts\Component::class,
            \PoPCMSSchema\CustomPostsWP\Component::class,
            \PoPCMSSchema\GenericCustomPosts\Component::class,
            \PoPCMSSchema\Media\Component::class,
            \PoPCMSSchema\Menus\Component::class,
            \PoPCMSSchema\Meta\Component::class,
            \PoPCMSSchema\Pages\Component::class,
            \PoPCMSSchema\PostCategories\Component::class,
            \PoPCMSSchema\PostCategoryMutations\Component::class,
            \PoPCMSSchema\PostMutations\Component::class,
            \PoPCMSSchema\Posts\Component::class,
            \PoPCMSSchema\PostTagMutations\Component::class,
            \PoPCMSSchema\PostTags\Component::class,
            \PoPCMSSchema\QueriedObject\Component::class,
            \PoPCMSSchema\Settings\Component::class,
            \PoPCMSSchema\Tags\Component::class,
            \PoPCMSSchema\TaxonomyMeta\Component::class,
            \PoPCMSSchema\UserAvatars\Component::class,
            \PoPCMSSchema\UserMeta\Component::class,
            \PoPCMSSchema\UserRoles\Component::class,
            \PoPCMSSchema\UserRolesWP\Component::class,
            \PoPCMSSchema\Users\Component::class,
            \PoPCMSSchema\UserState\Component::class,
            \PoPCMSSchema\UserStateMutations\Component::class,
            \PoPWPSchema\CommentMeta\Component::class,
            \PoPWPSchema\Comments\Component::class,
            \PoPWPSchema\CustomPostMeta\Component::class,
            \PoPWPSchema\CustomPosts\Component::class,
            \PoPWPSchema\Media\Component::class,
            \PoPWPSchema\Menus\Component::class,
            \PoPWPSchema\Meta\Component::class,
            \PoPWPSchema\Pages\Component::class,
            \PoPWPSchema\Posts\Component::class,
            \PoPWPSchema\SchemaCommons\Component::class,
            \PoPWPSchema\TaxonomyMeta\Component::class,
            \PoPWPSchema\UserMeta\Component::class,
            \PoPWPSchema\Users\Component::class,
        ];
    }
}
