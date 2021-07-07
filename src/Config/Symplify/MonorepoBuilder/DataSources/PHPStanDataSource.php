<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Symplify\MonorepoBuilder\DataSources;

class PHPStanDataSource
{
    public function getLevel(): int|string
    {
        return 5;
    }

    /**
     * @return string[]
     */
    public function getUnmigratedFailingPackages(): array
    {
        return [
            'layers/GraphQLByPoP/packages/graphql-parser',
            'layers/Schema/packages/block-metadata-for-wp',
            'layers/Schema/packages/categories',
            'layers/Schema/packages/categories',
            'layers/Schema/packages/comment-mutations-wp',
            'layers/Schema/packages/comment-mutations',
            'layers/Schema/packages/comments',
            'layers/Schema/packages/custompostmedia',
            'layers/Schema/packages/customposts',
            'layers/Schema/packages/generic-customposts',
            'layers/Schema/packages/highlights-wp',
            'layers/Schema/packages/highlights',
            'layers/Schema/packages/media',
            'layers/Schema/packages/menus-wp',
            'layers/Schema/packages/menus',
            'layers/Schema/packages/notifications',
            'layers/Schema/packages/pages',
            'layers/Schema/packages/post-categories',
            'layers/Schema/packages/post-tags',
            'layers/Schema/packages/posts-wp',
            'layers/Schema/packages/posts',
            'layers/Schema/packages/stances-wp',
            'layers/Schema/packages/stances',
            'layers/Schema/packages/tags',
            'layers/Schema/packages/user-roles-wp',
            'layers/Schema/packages/user-state-mutations',
            'layers/Schema/packages/users',
            'layers/SiteBuilder/packages/application-wp',
            'layers/SiteBuilder/packages/application',
            'layers/SiteBuilder/packages/component-model-configuration',
            'layers/SiteBuilder/packages/definitionpersistence',
            'layers/SiteBuilder/packages/definitions-base36',
            'layers/SiteBuilder/packages/definitions-emoji',
            'layers/SiteBuilder/packages/multisite',
            'layers/SiteBuilder/packages/resourceloader',
            'layers/SiteBuilder/packages/resources',
            'layers/SiteBuilder/packages/site-builder-api',
            'layers/SiteBuilder/packages/site-wp',
            'layers/SiteBuilder/packages/site',
            'layers/SiteBuilder/packages/spa',
            'layers/SiteBuilder/packages/static-site-generator',
            'layers/Wassup/packages/comment-mutations',
            'layers/Wassup/packages/contactus-mutations',
            'layers/Wassup/packages/contactuser-mutations',
            'layers/Wassup/packages/custompost-mutations',
            'layers/Wassup/packages/custompostlink-mutations',
            'layers/Wassup/packages/flag-mutations',
            'layers/Wassup/packages/form-mutations',
            'layers/Wassup/packages/gravityforms-mutations',
            'layers/Wassup/packages/highlight-mutations',
            'layers/Wassup/packages/newsletter-mutations',
            'layers/Wassup/packages/notification-mutations',
            'layers/Wassup/packages/post-mutations',
            'layers/Wassup/packages/postlink-mutations',
            'layers/Wassup/packages/share-mutations',
            'layers/Wassup/packages/socialnetwork-mutations',
            'layers/Wassup/packages/stance-mutations',
            'layers/Wassup/packages/system-mutations',
            'layers/Wassup/packages/user-state-mutations',
            'layers/Wassup/packages/volunteer-mutations',
            // 'layers/Legacy/Schema/packages/everythingelse-wp',
            // 'layers/Legacy/Schema/packages/everythingelse',
            // 'layers/Legacy/Wassup/packages/everythingelse-mutations',
            // 'layers/Legacy/Wassup/packages/wassup',
        ];
    }
}
