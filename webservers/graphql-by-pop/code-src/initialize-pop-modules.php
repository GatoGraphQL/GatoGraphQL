<?php

declare(strict_types=1);

use PoP\Root\App;

App::stockAndInitializeModuleClasses([
    \GraphQLByPoP\GraphQLServer\Module::class,
    \PoPAPI\RESTAPI\Module::class,
    \PoPCMSSchema\CategoryMutationsWP\Module::class,
    \PoPCMSSchema\CommentMutationsWP\Module::class,
    \PoPCMSSchema\MediaMutationsWP\Module::class,
    \PoPCMSSchema\CustomPostMediaMutationsWP\Module::class,
    \PoPCMSSchema\CustomPostMediaWP\Module::class,
    \PoPCMSSchema\CustomPostUserMutationsWP\Module::class,
    \PoPCMSSchema\PageMediaMutations\Module::class,
    \PoPCMSSchema\PostMediaMutations\Module::class,
    \PoPCMSSchema\PageMutationsWP\Module::class,
    \PoPCMSSchema\PagesWP\Module::class,
    \PoPCMSSchema\PostCategoriesWP\Module::class,
    \PoPCMSSchema\PostCategoryMutationsWP\Module::class,
    \PoPCMSSchema\PostMutations\Module::class,
    \PoPCMSSchema\PostsWP\Module::class,
    \PoPCMSSchema\PostTagMutationsWP\Module::class,
    \PoPCMSSchema\PostTagsWP\Module::class,
    \PoPCMSSchema\SettingsWP\Module::class,
    \PoPCMSSchema\TaxonomyQueryWP\Module::class,
    \PoPCMSSchema\UserAvatarsWP\Module::class,
    \PoPCMSSchema\UserRolesWP\Module::class,
    \PoPCMSSchema\UserStateMutationsWP\Module::class,
    \PoPCMSSchema\UserStateWP\Module::class,
    \PoPWPSchema\BlockMetadataWP\Module::class,
    \PoPWPSchema\CommentMeta\Module::class,
    \PoPWPSchema\CustomPostMeta\Module::class,
    \PoPWPSchema\Media\Module::class,
    \PoPWPSchema\Menus\Module::class,
    \PoPWPSchema\Pages\Module::class,
    \PoPWPSchema\Blocks\Module::class,
    \PoPWPSchema\Posts\Module::class,
    \PoPWPSchema\Site\Module::class,
    \PoPWPSchema\Multisite\Module::class,
    \PoPWPSchema\TaxonomyMeta\Module::class,
    \PoPWPSchema\UserMeta\Module::class,
]);

