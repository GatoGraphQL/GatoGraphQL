<?php

declare(strict_types=1);

use PoP\Root\App;

App::stockAndInitializeComponentClasses([
    \PoPCMSSchema\PostsWP\Component::class,
    \PoPCMSSchema\PagesWP\Component::class,
    \PoPCMSSchema\CustomPostMediaWP\Component::class,
    \PoPCMSSchema\TaxonomyQueryWP\Component::class,
    \PoPCMSSchema\UserStateWP\Component::class,
    \PoPCMSSchema\UserRolesWP\Component::class,
    \PoPCMSSchema\UserRolesACL\Component::class,
    \PoPCMSSchema\PostTagsWP\Component::class,
    \PoPCMSSchema\PostCategoriesWP\Component::class,
    \PoPCMSSchema\MenusWP\Component::class,
    \PoPCMSSchema\SettingsWP\Component::class,
    \PoPWPSchema\BlockMetadataWP\Component::class,
    \PoPCMSSchema\PostMutations\Component::class,
    \PoPCMSSchema\CustomPostMediaMutationsWP\Component::class,
    \PoPCMSSchema\PostTagMutationsWP\Component::class,
    \PoPCMSSchema\PostCategoryMutationsWP\Component::class,
    \PoPCMSSchema\CommentMutationsWP\Component::class,
    \PoPCMSSchema\UserStateMutationsWP\Component::class,
    \PoPCMSSchema\UserAvatarsWP\Component::class,
    \PoPCMSSchema\GenericCustomPosts\Component::class,
    \PoPWPSchema\Media\Component::class,
    \PoPWPSchema\Menus\Component::class,
    \PoPWPSchema\Pages\Component::class,
    \PoPWPSchema\Posts\Component::class,
    \PoPWPSchema\Users\Component::class,
    \PoPWPSchema\CommentMeta\Component::class,
    \PoPWPSchema\CustomPostMeta\Component::class,
    \PoPWPSchema\TaxonomyMeta\Component::class,
    \PoPWPSchema\UserMeta\Component::class,
    \GraphQLByPoP\GraphQLServer\Component::class,
    \PoP\RESTAPI\Component::class,
]);

