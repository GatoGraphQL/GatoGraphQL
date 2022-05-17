<?php

declare(strict_types=1);

use PoP\Root\App;

App::stockAndInitializeModuleClasses([
    \PoPCMSSchema\PostsWP\Module::class,
    \PoPCMSSchema\PagesWP\Module::class,
    \PoPCMSSchema\CustomPostMediaWP\Module::class,
    \PoPCMSSchema\TaxonomyQueryWP\Module::class,
    \PoPCMSSchema\UserStateWP\Module::class,
    \PoPCMSSchema\UserRolesWP\Module::class,
    \PoPCMSSchema\UserRolesACL\Module::class,
    \PoPCMSSchema\PostTagsWP\Module::class,
    \PoPCMSSchema\PostCategoriesWP\Module::class,
    \PoPCMSSchema\MenusWP\Module::class,
    \PoPCMSSchema\SettingsWP\Module::class,
    \PoPWPSchema\BlockMetadataWP\Module::class,
    \PoPCMSSchema\PostMutations\Module::class,
    \PoPCMSSchema\CustomPostMediaMutationsWP\Module::class,
    \PoPCMSSchema\PostTagMutationsWP\Module::class,
    \PoPCMSSchema\PostCategoryMutationsWP\Module::class,
    \PoPCMSSchema\CommentMutationsWP\Module::class,
    \PoPCMSSchema\UserStateMutationsWP\Module::class,
    \PoPCMSSchema\UserAvatarsWP\Module::class,
    \PoPCMSSchema\GenericCustomPosts\Module::class,
    \PoPWPSchema\Media\Module::class,
    \PoPWPSchema\Menus\Module::class,
    \PoPWPSchema\Pages\Module::class,
    \PoPWPSchema\Posts\Module::class,
    \PoPWPSchema\Users\Module::class,
    \PoPWPSchema\CommentMeta\Module::class,
    \PoPWPSchema\CustomPostMeta\Module::class,
    \PoPWPSchema\TaxonomyMeta\Module::class,
    \PoPWPSchema\UserMeta\Module::class,
    \GraphQLByPoP\GraphQLServer\Module::class,
    \PoPAPI\RESTAPI\Module::class,
]);

