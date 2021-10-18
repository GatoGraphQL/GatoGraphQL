<?php

declare(strict_types=1);

use PoP\Engine\AppLoader;

// Set the Component configuration
AppLoader::addComponentClassesToInitialize([
    \PoPSchema\CommentMetaWP\Component::class,
    \PoPSchema\PostsWP\Component::class,
    \PoPSchema\PagesWP\Component::class,
    \PoPSchema\CustomPostMetaWP\Component::class,
    \PoPSchema\CustomPostMediaWP\Component::class,
    \PoPSchema\TaxonomyQueryWP\Component::class,
    \PoPSchema\TaxonomyMetaWP\Component::class,
    \PoPSchema\UserMetaWP\Component::class,
    \PoPSchema\UserStateWP\Component::class,
    \PoPSchema\UserRolesWP\Component::class,
    \PoPSchema\UserRolesACL\Component::class,
    \PoPSchema\PostTagsWP\Component::class,
    \PoPSchema\PostCategoriesWP\Component::class,
    \PoPSchema\MenusWP\Component::class,
    \PoPSchema\SettingsWP\Component::class,
    \PoPSchema\BlockMetadataWP\Component::class,
    \PoPSchema\PostMutations\Component::class,
    \PoPSchema\CustomPostMediaMutationsWP\Component::class,
    \PoPSchema\PostTagMutationsWP\Component::class,
    \PoPSchema\PostCategoryMutationsWP\Component::class,
    \PoPSchema\CommentMutationsWP\Component::class,
    \PoPSchema\UserStateMutationsWP\Component::class,
    \PoPSchema\UserAvatarsWP\Component::class,
    \PoPSchema\GenericCustomPosts\Component::class,
    \PoPWPSchema\Media\Component::class,
    \PoPWPSchema\Menus\Component::class,
    \PoPWPSchema\Pages\Component::class,
    \PoPWPSchema\Posts\Component::class,
    \PoPWPSchema\Users\Component::class,
    \GraphQLByPoP\GraphQLServer\Component::class,
    \PoP\FunctionFields\Component::class,
    \PoP\RESTAPI\Component::class,
]);

