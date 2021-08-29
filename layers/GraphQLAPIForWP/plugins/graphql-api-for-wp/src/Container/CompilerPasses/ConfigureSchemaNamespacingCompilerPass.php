<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Container\CompilerPasses;

use GraphQLAPI\GraphQLAPI\Security\UserAuthorizationInterface;
use PoP\ComponentModel\Schema\SchemaNamespacingServiceInterface;
use PoP\Engine\TypeResolvers\RootTypeResolver;
use PoP\Root\Container\CompilerPasses\AbstractCompilerPass;
use PoP\Root\Container\ContainerBuilderWrapperInterface;
use PoPSchema\UserRolesAccessControl\Services\AccessControlGroups as UserRolesAccessControlGroups;

class ConfigureSchemaNamespacingCompilerPass extends AbstractCompilerPass
{
    protected function doProcess(ContainerBuilderWrapperInterface $containerBuilderWrapper): void
    {
        $schemaNamespacingServiceDefinition = $containerBuilderWrapper->getDefinition(SchemaNamespacingServiceInterface::class);
        // The entities from the WordPress data model (Post, User, Comment, etc)
        // are considered the canonical source, so they do not need to be namespaced.
        $componentClasses = [
            \PoPSchema\CustomPosts\Component::class,
            \PoPSchema\CustomPostMedia\Component::class,
            \PoPWPSchema\CustomPosts\Component::class,
            \PoPSchema\GenericCustomPosts\Component::class,
            \PoPSchema\Posts\Component::class,
            \PoPWPSchema\Posts\Component::class,
            \PoPSchema\Comments\Component::class,
            \PoPSchema\Users\Component::class,
            \PoPSchema\UserState\Component::class,
            \PoPWPSchema\Users\Component::class,
            \PoPSchema\UserRoles\Component::class,
            \PoPSchema\UserAvatars\Component::class,
            \PoPSchema\Pages\Component::class,
            \PoPWPSchema\Pages\Component::class,
            \PoPSchema\CustomPostMedia\Component::class,
            \PoPSchema\Media\Component::class,
            \PoPWPSchema\Media\Component::class,
            \PoPSchema\Tags\Component::class,
            \PoPSchema\PostTags\Component::class,
            \PoPSchema\Categories\Component::class,
            \PoPSchema\PostCategories\Component::class,
            \PoPSchema\Menus\Component::class,
            \PoPWPSchema\Menus\Component::class,
            \PoPSchema\Settings\Component::class,
            \PoPSchema\UserStateMutations\Component::class,
            \PoPSchema\PostMutations\Component::class,
            \PoPSchema\CustomPostMediaMutations\Component::class,
            \PoPSchema\PostTagMutations\Component::class,
            \PoPSchema\PostCategoryMutations\Component::class,
            \PoPSchema\CommentMutations\Component::class,
            \PoPSchema\CustomPostMeta\Component::class,
            \PoPSchema\UserMeta\Component::class,
            \PoPSchema\CommentMeta\Component::class,
            \PoPSchema\TaxonomyMeta\Component::class,
        ];
        foreach ($componentClasses as $componentClass) {
            $componentClassNamespace = substr($componentClass, strrpos($componentClass, '\\') + 1);
            $schemaNamespacingServiceDefinition->addMethodCall(
                'addSchemaNamespaceForClassOwnerAndProjectNamespace',
                [
                    $componentClassNamespace,
                    '' // Empty namespace
                ]
            );
        }
    }
}
