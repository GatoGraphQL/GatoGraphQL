<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

/**
 * This Rector configuration imports the fully qualified classnames
 * using `use`, and removing it from the body.
 * Rule `AndAssignsToSeparateLinesRector` is not needed, but we need
 * to run at least 1 rule.
 */
return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RemoveUselessParamTagRector::class);
    $services->set(RemoveUselessReturnTagRector::class);
    
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::AUTO_IMPORT_NAMES, true);
    $parameters->set(Option::IMPORT_SHORT_CLASSES, false);

    $monorepoDir = dirname(__DIR__, 5);

    // Rector relies on autoload setup of your project; Composer autoload is included by default; to add more:
    $parameters->set(Option::BOOTSTRAP_FILES, [
        // full directory
        $monorepoDir . '/vendor/php-stubs/wordpress-stubs/wordpress-stubs.php',
    ]);

    // files to skip downgrading
    $parameters->set(Option::PATHS, [
        $monorepoDir . '/layers/Schema/packages/events/src/TypeResolvers/ObjectType/EventObjectTypeResolver.php',
        $monorepoDir . '/layers/Schema/packages/locations/src/TypeResolvers/ObjectType/LocationObjectTypeResolver.php',
        $monorepoDir . '/layers/Wassup/packages/locationposts/src/TypeResolvers/ObjectType/LocationPostObjectTypeResolver.php',
        $monorepoDir . '/submodules/PoP/layers/Engine/packages/engine/src/TypeResolvers/ObjectType/RootObjectTypeResolver.php',
        $monorepoDir . '/submodules/PoP/layers/GraphQLByPoP/packages/graphql-server/src/TypeResolvers/ObjectType/AbstractUseRootAsSourceForSchemaObjectTypeResolver.php',
        $monorepoDir . '/submodules/PoP/layers/GraphQLByPoP/packages/graphql-server/src/TypeResolvers/ObjectType/DirectiveObjectTypeResolver.php',
        $monorepoDir . '/submodules/PoP/layers/GraphQLByPoP/packages/graphql-server/src/TypeResolvers/ObjectType/EnumValueObjectTypeResolver.php',
        $monorepoDir . '/submodules/PoP/layers/GraphQLByPoP/packages/graphql-server/src/TypeResolvers/ObjectType/FieldObjectTypeResolver.php',
        $monorepoDir . '/submodules/PoP/layers/GraphQLByPoP/packages/graphql-server/src/TypeResolvers/ObjectType/InputValueObjectTypeResolver.php',
        $monorepoDir . '/submodules/PoP/layers/GraphQLByPoP/packages/graphql-server/src/TypeResolvers/ObjectType/MutationRootObjectTypeResolver.php',
        $monorepoDir . '/submodules/PoP/layers/GraphQLByPoP/packages/graphql-server/src/TypeResolvers/ObjectType/QueryRootObjectTypeResolver.php',
        $monorepoDir . '/submodules/PoP/layers/GraphQLByPoP/packages/graphql-server/src/TypeResolvers/ObjectType/TypeObjectTypeResolver.php',
        $monorepoDir . '/submodules/PoP/layers/Schema/packages/comments/src/TypeResolvers/ObjectType/CommentObjectTypeResolver.php',
        $monorepoDir . '/submodules/PoP/layers/Schema/packages/customposts/src/TypeResolvers/ObjectType/CustomPostObjectTypeResolver.php',
        $monorepoDir . '/submodules/PoP/layers/Schema/packages/customposts/src/TypeResolvers/UnionType/CustomPostUnionTypeResolver.php',
        $monorepoDir . '/submodules/PoP/layers/Schema/packages/generic-customposts/src/TypeResolvers/ObjectType/GenericCustomPostObjectTypeResolver.php',
        $monorepoDir . '/submodules/PoP/layers/Schema/packages/media/src/TypeResolvers/ObjectType/MediaObjectTypeResolver.php',
        $monorepoDir . '/submodules/PoP/layers/Schema/packages/menus/src/TypeResolvers/ObjectType/MenuItemObjectTypeResolver.php',
        $monorepoDir . '/submodules/PoP/layers/Schema/packages/menus/src/TypeResolvers/ObjectType/MenuObjectTypeResolver.php',
        $monorepoDir . '/submodules/PoP/layers/Schema/packages/pages/src/TypeResolvers/ObjectType/PageObjectTypeResolver.php',
        $monorepoDir . '/submodules/PoP/layers/Schema/packages/post-categories/src/TypeResolvers/ObjectType/PostCategoryObjectTypeResolver.php',
        $monorepoDir . '/submodules/PoP/layers/Schema/packages/post-tags/src/TypeResolvers/ObjectType/PostTagObjectTypeResolver.php',
        $monorepoDir . '/submodules/PoP/layers/Schema/packages/posts/src/TypeResolvers/ObjectType/PostObjectTypeResolver.php',
        $monorepoDir . '/submodules/PoP/layers/Schema/packages/user-avatars/src/TypeResolvers/ObjectType/UserAvatarObjectTypeResolver.php',
        $monorepoDir . '/submodules/PoP/layers/Schema/packages/user-roles-wp/src/TypeResolvers/ObjectType/UserRoleObjectTypeResolver.php',
        $monorepoDir . '/submodules/PoP/layers/Schema/packages/users/src/TypeResolvers/ObjectType/UserObjectTypeResolver.php',
        $monorepoDir . '/submodules/PoP/layers/SiteBuilder/packages/multisite/src/TypeResolvers/ObjectType/SiteObjectTypeResolver.php',
    ]);

    // files to skip downgrading
    $parameters->set(Option::SKIP, [
        '*/migrate-*',
        '*/vendor/*',
        '*/node_modules/*',
    ]);

    // /**
    //  * This constant is defined in wp-load.php, but never loaded.
    //  * It is read when resolving class WP_Upgrader in Plugin.php.
    //  * Define it here again, or otherwise Rector fails with message:
    //  *
    //  * "PHP Warning:  Use of undefined constant ABSPATH -
    //  * assumed 'ABSPATH' (this will throw an Error in a future version of PHP)
    //  * in .../graphql-api-for-wp/vendor/wordpress/wordpress/wp-admin/includes/class-wp-upgrader.php
    //  * on line 13"
    //  */
    // /** Define ABSPATH as this file's directory */
    // if (!defined('ABSPATH')) {
    //     define('ABSPATH', $monorepoDir . '/vendor/wordpress/wordpress/');
    // }
};
