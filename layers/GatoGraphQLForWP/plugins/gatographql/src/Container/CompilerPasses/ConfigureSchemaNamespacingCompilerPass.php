<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Container\CompilerPasses;

use PoP\Root\Module\ModuleInterface;

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
     * Also include the Gato-owned schema as canonical,
     * because it includes classes such as `ErrorPayload`
     * or `IdentifiableObject` which are not nice to
     * prepend with `Gato_`.
     *
     * @return string[]
     * @phpstan-return array<class-string<ModuleInterface>>
     */
    protected function getModuleClasses(): array
    {
        return [
            // Gato-owned schema
            \GraphQLByPoP\GraphQLServer\Module::class,
            \PoP\ComponentModel\Module::class,
            \PoP\Engine\Module::class,
            \PoPSchema\HTTPRequests\Module::class,
            \PoPSchema\DirectiveCommons\Module::class,
            \PoPSchema\ExtendedSchemaCommons\Module::class,
            \PoPSchema\SchemaCommons\Module::class,

            // WordPress schema
            \PoPCMSSchema\Categories\Module::class,
            \PoPCMSSchema\CommentMeta\Module::class,
            \PoPCMSSchema\CommentMutations\Module::class,
            \PoPCMSSchema\Comments\Module::class,
            \PoPCMSSchema\Taxonomies\Module::class,
            \PoPCMSSchema\CustomPostMutations\Module::class,
            \PoPCMSSchema\CustomPostCategoryMutations\Module::class,
            \PoPCMSSchema\CustomPostTagMutations\Module::class,
            \PoPCMSSchema\CustomPostMedia\Module::class,
            \PoPCMSSchema\CustomPostMedia\Module::class,
            \PoPCMSSchema\CustomPostMediaMutations\Module::class,
            \PoPCMSSchema\CustomPostUserMutations\Module::class,
            \PoPCMSSchema\CustomPostUserMutationsWP\Module::class,
            \PoPCMSSchema\MediaMutations\Module::class,
            \PoPCMSSchema\PageMediaMutations\Module::class,
            \PoPCMSSchema\PostMediaMutations\Module::class,
            \PoPCMSSchema\CustomPostMeta\Module::class,
            \PoPCMSSchema\CustomPosts\Module::class,
            \PoPCMSSchema\CustomPostsWP\Module::class,
            \PoPCMSSchema\Media\Module::class,
            \PoPCMSSchema\Menus\Module::class,
            \PoPCMSSchema\Meta\Module::class,
            \PoPCMSSchema\PageMutations\Module::class,
            \PoPCMSSchema\Pages\Module::class,
            \PoPCMSSchema\PostCategories\Module::class,
            \PoPCMSSchema\PostCategoryMutations\Module::class,
            \PoPCMSSchema\PostCategoryMutationsWP\Module::class,
            \PoPCMSSchema\PostMutations\Module::class,
            \PoPCMSSchema\Posts\Module::class,
            \PoPCMSSchema\PostTagMutations\Module::class,
            \PoPCMSSchema\PostTagMutationsWP\Module::class,
            \PoPCMSSchema\PostTags\Module::class,
            \PoPCMSSchema\QueriedObject\Module::class,
            \PoPCMSSchema\Settings\Module::class,
            \PoPCMSSchema\Tags\Module::class,
            \PoPCMSSchema\TaxonomyMeta\Module::class,
            \PoPCMSSchema\UserAvatars\Module::class,
            \PoPCMSSchema\UserMeta\Module::class,
            \PoPCMSSchema\UserRoles\Module::class,
            \PoPCMSSchema\UserRolesWP\Module::class,
            \PoPCMSSchema\Users\Module::class,
            \PoPCMSSchema\UserState\Module::class,
            \PoPCMSSchema\UserStateMutations\Module::class,
            \PoPWPSchema\CommentMeta\Module::class,
            \PoPWPSchema\Comments\Module::class,
            \PoPWPSchema\CustomPostMeta\Module::class,
            \PoPWPSchema\CustomPosts\Module::class,
            \PoPWPSchema\Media\Module::class,
            \PoPWPSchema\Menus\Module::class,
            \PoPWPSchema\Meta\Module::class,
            \PoPWPSchema\Pages\Module::class,
            \PoPWPSchema\Posts\Module::class,
            \PoPWPSchema\Blocks\Module::class,
            \PoPWPSchema\TaxonomyMeta\Module::class,
            \PoPWPSchema\UserMeta\Module::class,
            \PoPWPSchema\Users\Module::class,
            \PoPCMSSchema\SchemaCommons\Module::class,
            \PoPCMSSchema\SchemaCommonsWP\Module::class,
            \PoPWPSchema\SchemaCommons\Module::class,
        ];
    }
}
