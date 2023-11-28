<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Constants;

/**
 * Recipes which have not been implemented, but are still needed to add predefined persisted queries
 */
class VirtualRecipes
{
    public const IMPORTING_A_POST_FROM_WORDPRESS_RSS_FEED = 'importing-a-post-from-wordpress-rss-feed';
    public const IMPORTING_POSTS_FROM_A_CSV = 'importing-posts-from-a-csv';
    public const TRANSLATING_CLASSIC_EDITOR_POST_TO_A_DIFFERENT_LANGUAGE = 'translating-classic-editor-post-to-a-different-language';
    public const BULK_TRANSLATING_CLASSIC_EDITOR_POSTS_TO_A_DIFFERENT_LANGUAGE = 'bulk-translating-classic-editor-posts-to-a-different-language';
    public const FETCH_POST_LINKS = 'fetch-post-links';
}
