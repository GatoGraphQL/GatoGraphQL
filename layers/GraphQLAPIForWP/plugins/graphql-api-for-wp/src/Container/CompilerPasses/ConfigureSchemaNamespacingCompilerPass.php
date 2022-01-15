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
            \PoPSchema\Categories\Component::class,
            \PoPSchema\CommentMeta\Component::class,
            \PoPSchema\CommentMutations\Component::class,
            \PoPSchema\Comments\Component::class,
            \PoPSchema\SchemaCommons\Component::class,
            \PoPSchema\SchemaCommonsWP\Component::class,
            \PoPSchema\CustomPostMedia\Component::class,
            \PoPSchema\CustomPostMedia\Component::class,
            \PoPSchema\CustomPostMediaMutations\Component::class,
            \PoPSchema\PostMediaMutations\Component::class,
            \PoPSchema\CustomPostMeta\Component::class,
            \PoPSchema\CustomPosts\Component::class,
            \PoPSchema\CustomPostsWP\Component::class,
            \PoPSchema\GenericCustomPosts\Component::class,
            \PoPSchema\Media\Component::class,
            \PoPSchema\Menus\Component::class,
            \PoPSchema\Meta\Component::class,
            \PoPSchema\Pages\Component::class,
            \PoPSchema\PostCategories\Component::class,
            \PoPSchema\PostCategoryMutations\Component::class,
            \PoPSchema\PostMutations\Component::class,
            \PoPSchema\Posts\Component::class,
            \PoPSchema\PostTagMutations\Component::class,
            \PoPSchema\PostTags\Component::class,
            \PoPSchema\QueriedObject\Component::class,
            \PoPSchema\Settings\Component::class,
            \PoPSchema\Tags\Component::class,
            \PoPSchema\TaxonomyMeta\Component::class,
            \PoPSchema\UserAvatars\Component::class,
            \PoPSchema\UserMeta\Component::class,
            \PoPSchema\UserRoles\Component::class,
            \PoPSchema\UserRolesWP\Component::class,
            \PoPSchema\Users\Component::class,
            \PoPSchema\UserState\Component::class,
            \PoPSchema\UserStateMutations\Component::class,
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
