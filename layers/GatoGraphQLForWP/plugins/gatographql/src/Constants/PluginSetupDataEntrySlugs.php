<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Constants;

class PluginSetupDataEntrySlugs
{
    /**
     * @gatographql-note: Do not rename this slug, as it's referenced when installing the testing webservers
     */
    public final const SCHEMA_CONFIGURATION_NESTED_MUTATIONS = 'nested-mutations';
    /**
     * @gatographql-note: Do not rename this slug, as it's referenced when installing the testing webservers
     */
    public final const SCHEMA_CONFIGURATION_BULK_MUTATIONS = 'bulk-mutations';

    public final const CUSTOM_ENDPOINT_INTERNAL = 'internal';
    public final const CUSTOM_ENDPOINT_NESTED_MUTATIONS = 'nested-mutations';
    public final const CUSTOM_ENDPOINT_BULK_MUTATIONS = 'bulk-mutations';

    public final const PERSISTED_QUERY_ADD_COMMENTS_BLOCK_TO_POST = 'add-comments-block-to-post';
    public final const PERSISTED_QUERY_ADD_MISSING_LINKS_IN_POST = 'add-missing-links-in-post';
    public final const PERSISTED_QUERY_CREATE_MISSING_TRANSLATION_CATEGORIES_FOR_POLYLANG = 'create-missing-translation-categories-for-polylang';
    public final const PERSISTED_QUERY_CREATE_MISSING_TRANSLATION_MEDIA_FOR_POLYLANG = 'create-missing-translation-media-for-polylang';
    public final const PERSISTED_QUERY_CREATE_MISSING_TRANSLATION_POSTS_FOR_POLYLANG = 'create-missing-translation-posts-for-polylang';
    public final const PERSISTED_QUERY_CREATE_MISSING_TRANSLATION_TAGS_FOR_POLYLANG = 'create-missing-translation-tags-for-polylang';
    public final const PERSISTED_QUERY_DUPLICATE_POST = 'duplicate-post';
    public final const PERSISTED_QUERY_DUPLICATE_POSTS = 'duplicate-posts';
    public final const PERSISTED_QUERY_EXPORT_POST_TO_WORDPRESS_SITE = 'export-post-to-wordpress-site';
    public final const PERSISTED_QUERY_FETCH_COMMENTS_BY_PERIOD = 'fetch-comments-by-period';
    public final const PERSISTED_QUERY_FETCH_IMAGE_URLS_IN_BLOCKS = 'fetch-image-urls-in-blocks';
    public final const PERSISTED_QUERY_FETCH_POST_LINKS = 'fetch-post-links';
    public final const PERSISTED_QUERY_FETCH_POSTS_BY_THUMBNAIL = 'fetch-posts-by-thumbnail';
    public final const PERSISTED_QUERY_FETCH_USERS_BY_LOCALE = 'fetch-users-by-locale';
    public final const PERSISTED_QUERY_GENERATE_A_POSTS_FEATURED_IMAGE_USING_AI_AND_OPTIMIZE_IT = 'generate-a-posts-featured-image-using-ai-and-optimize-it';
    public final const PERSISTED_QUERY_IMPORT_HTML_FROM_URLS_AS_NEW_POSTS_IN_WORDPRESS = 'import-html-from-urls-as-new-posts-in-wordpress';
    public final const PERSISTED_QUERY_IMPORT_NEW_POSTS_FROM_WORDPRESS_RSS_FEED = 'import-new-posts-from-wordpress-rss-feed';
    public final const PERSISTED_QUERY_IMPORT_POST_FROM_WORDPRESS_RSS_FEED = 'import-post-from-wordpress-rss-feed';
    public final const PERSISTED_QUERY_IMPORT_POST_FROM_WORDPRESS_RSS_FEED_AND_REWRITE_ITS_CONTENT_WITH_CHATGPT = 'import-post-from-wp-rss-feed-and-rewrite-content-with-chatgpt';
    public final const PERSISTED_QUERY_IMPORT_POST_FROM_WORDPRESS_SITE = 'import-post-from-wordpress-site';
    public final const PERSISTED_QUERY_IMPORT_POSTS_FROM_CSV = 'import-posts-from-csv';
    public final const PERSISTED_QUERY_INSERT_BLOCK_IN_POST = 'insert-block-in-post';
    public final const PERSISTED_QUERY_INSERT_BLOCK_IN_POSTS = 'insert-block-in-posts';
    public final const PERSISTED_QUERY_REGEX_REPLACE_STRINGS_IN_POST = 'regex-replace-strings-in-post';
    public final const PERSISTED_QUERY_REGEX_REPLACE_STRINGS_IN_POSTS = 'regex-replace-strings-in-posts';
    public final const PERSISTED_QUERY_REGISTER_A_NEWSLETTER_SUBSCRIBER_FROM_INSTAWP_TO_MAILCHIMP = 'register-a-newsletter-subscriber-from-instawp-to-mailchimp';
    public final const PERSISTED_QUERY_REMOVE_BLOCK_FROM_POSTS = 'remove-block-from-posts';
    public final const PERSISTED_QUERY_REPLACE_DOMAIN_IN_POSTS = 'replace-domain-in-posts';
    public final const PERSISTED_QUERY_REPLACE_HTTP_WITH_HTTPS_IN_IMAGE_SOURCES_IN_POST = 'replace-http-with-https-in-image-sources-in-post';
    public final const PERSISTED_QUERY_REPLACE_POST_SLUG_IN_POSTS = 'replace-post-slug-in-posts';
    public final const PERSISTED_QUERY_REPLACE_STRINGS_IN_POST = 'replace-strings-in-post';
    public final const PERSISTED_QUERY_REPLACE_STRINGS_IN_POSTS = 'replace-strings-in-posts';
    public final const PERSISTED_QUERY_SEND_EMAIL_TO_ADMIN_ABOUT_POST = 'send-email-to-admin-about-post';
    public final const PERSISTED_QUERY_SEND_EMAIL_TO_USERS_ABOUT_POST = 'send-email-to-users-about-post';
    public final const PERSISTED_QUERY_SYNC_FEATUREDIMAGE_FOR_POLYLANG = 'sync-featuredimage-for-polylang';
    public final const PERSISTED_QUERY_SYNC_CATEGORIES_FOR_POLYLANG = 'sync-categories-for-polylang';
    public final const PERSISTED_QUERY_SYNC_TAGS_FOR_POLYLANG = 'sync-tags-for-polylang';
    public final const PERSISTED_QUERY_TRANSLATE_AND_CREATE_ALL_PAGES_FOR_MULTILINGUAL_WORDPRESS_SITE_CLASSIC_EDITOR = 'translate-and-create-all-pages-for-multilingual-wordpress-site-classic-editor';
    public final const PERSISTED_QUERY_TRANSLATE_AND_CREATE_ALL_PAGES_FOR_MULTILINGUAL_WORDPRESS_SITE_GUTENBERG = 'translate-and-create-all-pages-for-multilingual-wordpress-site-gutenberg';
    public final const PERSISTED_QUERY_TRANSLATE_CATEGORIES_FOR_MULTILINGUALPRESS = 'translate-categories-for-multilingualpress';
    public final const PERSISTED_QUERY_TRANSLATE_CATEGORIES_FOR_POLYLANG = 'translate-categories-for-polylang';
    public final const PERSISTED_QUERY_TRANSLATE_MEDIA_FOR_POLYLANG = 'translate-media-for-polylang';
    public final const PERSISTED_QUERY_TRANSLATE_CONTENT_FROM_URL = 'translate-content-from-url';
    public final const PERSISTED_QUERY_TRANSLATE_POEDIT_FILE_CONTENT = 'translate-poedit-file-content';
    public final const PERSISTED_QUERY_TRANSLATE_POST_CLASSIC_EDITOR = 'translate-post-classic-editor';
    public final const PERSISTED_QUERY_TRANSLATE_POST_GUTENBERG = 'translate-post-gutenberg';
    public final const PERSISTED_QUERY_TRANSLATE_POSTS_CLASSIC_EDITOR = 'translate-posts-classic-editor';
    public final const PERSISTED_QUERY_TRANSLATE_POSTS_GUTENBERG = 'translate-posts-gutenberg';
    public final const PERSISTED_QUERY_TRANSLATE_POSTS_FOR_POLYLANG_CLASSIC_EDITOR = 'translate-posts-for-polylang-classic-editor';
    public final const PERSISTED_QUERY_TRANSLATE_POSTS_FOR_POLYLANG_GUTENBERG = 'translate-posts-for-polylang-gutenberg';
    public final const PERSISTED_QUERY_TRANSLATE_POSTS_FOR_MULTILINGUALPRESS_CLASSIC_EDITOR = 'translate-posts-for-multilingualpress-classic-editor';
    public final const PERSISTED_QUERY_TRANSLATE_POSTS_FOR_MULTILINGUALPRESS_GUTENBERG = 'translate-posts-for-multilingualpress-gutenberg';
    public final const PERSISTED_QUERY_TRANSLATE_TAGS_FOR_MULTILINGUALPRESS = 'translate-tags-for-multilingualpress';
    public final const PERSISTED_QUERY_TRANSLATE_TAGS_FOR_POLYLANG = 'translate-tags-for-polylang';
}
