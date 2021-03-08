<?php

declare(strict_types=1);

use PoP\Engine\AppLoader;

// Set the Component configuration
AppLoader::addComponentClassesToInitialize([
    \PoPSchema\ConvertCaseDirectives\Component::class,
    \PoPSchema\CDNDirective\Component::class,
    \PoPSchema\CommentMetaWP\Component::class,
    \PoPSchema\PostsWP\Component::class,
    \PoPSchema\PagesWP\Component::class,
    \PoPSchema\CustomPostMetaWP\Component::class,
    \PoPSchema\CustomPostMediaWP\Component::class,
    \PoPSchema\TaxonomyQueryWP\Component::class,
    \PoPSchema\UserMetaWP\Component::class,
    \PoPSchema\UserStateWP\Component::class,
    \PoPSchema\UserRolesWP\Component::class,
    \PoPSchema\UserRolesACL\Component::class,
    \PoPSchema\PostTagsWP\Component::class,
    \PoPSchema\BlockMetadataWP\Component::class,
    \PoPSchema\GoogleTranslateDirectiveForCustomPosts\Component::class,
    \PoPSchema\PostMutations\Component::class,
    \PoPSchema\CustomPostMediaMutationsWP\Component::class,
    \PoPSchema\CommentMutationsWP\Component::class,
    \PoPSchema\UserStateMutationsWP\Component::class,
    \PoPSchema\GenericCustomPosts\Component::class,
    \GraphQLByPoP\GraphQLServer\Component::class,
    \PoP\FunctionFields\Component::class,
    \PoP\RESTAPI\Component::class,
    \PoP\TraceTools\Component::class,
    \Leoloso\ExamplesForPoP\Component::class,
]);

