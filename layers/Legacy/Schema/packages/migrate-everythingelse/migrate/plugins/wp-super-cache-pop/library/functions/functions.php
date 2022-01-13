<?php

// Remove all cache deleting when a comment is added or updated. That is because the property "Only refresh current page when comments made."
// doesn't work, because the logic reads:
// if ( isset( $wp_cache_refresh_single_only ) && $wp_cache_refresh_single_only && ( strpos( $_SERVER[ 'HTTP_REFERER' ], 'edit-comments.php' ) || strpos( $_SERVER[ 'REQUEST_URI' ], 'wp-comments-post.php' ) ) ) {
// and since we're not coming from wp-comments-post.php then this logic never works, and for every comment added the whole cache is deleted
// This theme can operate without deleting the cache, since comments are retrieved on a 2nd request, which is never cached to start with
\PoP\Root\App::addAction('init', 'popWpscRemoveCommentActions');
function popWpscRemoveCommentActions()
{
    \PoP\Root\App::removeAction('trackback_post', 'wp_cache_get_postid_from_comment', 99);
    \PoP\Root\App::removeAction('pingback_post', 'wp_cache_get_postid_from_comment', 99);
    \PoP\Root\App::removeAction('comment_post', 'wp_cache_get_postid_from_comment', 99);
    \PoP\Root\App::removeAction('edit_comment', 'wp_cache_get_postid_from_comment', 99);
    \PoP\Root\App::removeAction('wp_set_comment_status', 'wp_cache_get_postid_from_comment', 99, 2);
}

function popWpscIsStacktraceComingFromComments()
{

    // Stack trace:
    // array(16) {
    //   [0]=>array {
    //     ["file"]=>"wp-content/plugins/poptheme-wassup/plugins/wp-super-cache/library/functions.php"
    //     ["function"]=>"popWpscIsStacktraceComingFromComments"
    //   }
    //   [1]=>array {
    //     ["file"]=>"wp-includes/plugin.php"
    //     ["function"]=>"popWpscRemoveforcomments"
    //   }
    //   [2]=>array {
    //     ["file"]=>"wp-includes/post.php"
    //     ["function"]=>"\PoP\Root\App::doAction"
    //   }
    //   [3]=>array {
    //     ["file"]=>"wp-includes/comment.php"
    //     ["function"]=>"clean_post_cache"
    //   }
    //   [4]=>array {
    //     ["file"]=>"wp-includes/comment.php"
    //     ["function"]=>"wp_update_comment_count_now"
    //   }
    //   ...
    // }

    $stack = debug_backtrace();
    return ($stack[4] && $stack[4]['function'] == 'wp_update_comment_count_now');
}

// Priority 9: execute before \PoP\Root\App::addAction( 'clean_post_cache', 'wp_cache_post_edit' );
\PoP\Root\App::addAction('clean_post_cache', 'popWpscRemoveforcomments', 9, 1);
function popWpscRemoveforcomments($post_id)
{
    if (popWpscIsStacktraceComingFromComments()) {
        // This one must also be removed because it's called from function wp_update_comment_count_now($post_id) in comment.php
        // Check if this function is on the stack, then remove the WP Super Cache hook
        \PoP\Root\App::removeAction('clean_post_cache', 'wp_cache_post_edit');

        // But still remove all the /loaders/posts cached pages, since that's where the comments are brought
        popWpscDeletecommentscache($post_id);
    }
}
function popWpscDeletecommentscache($post_id)
{

    // Code copied from function wp_cache_phase2_clean_cache($file_prefix)
    global $cache_path, $blog_cache_dir, $file_prefix, $blog_id;

    if (!wp_cache_writers_entry()) {
        return false;
    }

    // Comment Leo 11/01/2017: do not check the path, only the parameters, so that any page that has pid=$post_id gets deleted
    // For sure there are 2 such pages: POP_POSTS_ROUTE_LOADERS_POSTS_LAYOUTS and POP_POSTS_ROUTE_LOADERS_POSTS_FIELDS
    // Check that the path is in the cache URI
    // $path = POP_POSTS_ROUTE_LOADERS_POSTS_LAYOUTS;

    // Check that the param 'pid' with the right post_id is in the URI
    // It can be of the shape pid=$post_id, pid[]=$post_id, or pid[3]=$post_id
    // Example of hit:
    // "uri":"localhost\/en\/add-comment\/?pid=23787&target=addons&module=settingsdata&output=json&theme=wassup&thememode=sliding&themestyle=swift&settingsformat="
    // Explanation of the regex:
    // These 2 below wouldn't work! So it's not done:
    // Such regex that didn't work: $regex = '/"uri":"(.*)[\?|&]'.'pid'.'(\[[0-9]+\])?='.$post_id.'(&|")/';
    // - search in field "uri":"
    // - then anything
    // - before 'pid' there's either ? or &
    // - after 'pid' can have =, []=, or [number]=
    // - then must have $post_id
    // - then, it must either have & or be the end
    $regex = '/[\?|&]'.\PoPSchema\Posts\Constants\InputNames::POST_ID.'(\[[0-9]+\])?='.$post_id.'(&|")/';

    // wp_cache_debug( "wp_cache_phase2_clean_cache: Cleaning cache in $blog_cache_dir" );
    if ($handle = @opendir($blog_cache_dir)) {
        while (false !== ($file = @readdir($handle))) {
            if (strpos($file, $file_prefix) !== false) {
                if (strpos($file, '.html')) {
                    // delete old legacy files immediately
                    // wp_cache_debug( "wp_cache_phase2_clean_cache: Deleting obsolete legacy cache+meta files: $file" );
                    // @unlink( $blog_cache_dir . $file);
                    // @unlink( $blog_cache_dir . 'meta/' . str_replace( '.html', '.meta', $file ) );
                } else {
                    $meta = json_decode(wp_cache_get_legacy_cache($blog_cache_dir . 'meta/' . $file), true);
                    if ($meta[ 'blog_id' ] == /*$wpdb->blogid*/$blog_id && /*strpos($meta['uri'], $path) !== false && */preg_match($regex, $meta['uri'])) {
                        wp_cache_debug("popWpscRemoveforcomments: Deleting file: $file with uri " . $meta['uri']);
                        @unlink($blog_cache_dir . $file);
                        @unlink($blog_cache_dir . 'meta/' . $file);
                    }
                }
            }
        }
        closedir($handle);
    }
    wp_cache_writers_exit();
}

/**
 * WP Super Cache Plugin functions
 */

// In function wp_cache_phase2() in file wp-super-cache/wp-cache-phase2.php it doesn't delete the cache when transitioning a post from publish to draft, do it here then
\PoP\Root\App::addAction('publish_to_draft', 'gdWpscPublishToDraft', 0);
function gdWpscPublishToDraft($post_id)
{
    define('WPSCFORCEUPDATE', true); // Added so that it also deletes the draft, only when transitioning, not always
    wp_cache_post_edit($post_id);
}

function gdWpCacheUserEdit()
{

    // Delete the cache when the information is updated
    // Copied from plugins/wp-super-cache/wp-cache-phase2.php function wp_cache_post_edit($post_id)

    // Only do it if the Cache is enabled
    global $cache_enabled;
    if (!$cache_enabled) {
        return;
    }

    global $blog_cache_dir, $wp_cache_object_cache;
    if ($wp_cache_object_cache) {
        reset_oc_version();
    } else {
        prune_super_cache($blog_cache_dir, true);
        prune_super_cache(get_supercache_dir(), true);
    }
}

/**
 * Cache the JSON response (starts with { and finishes with } or starts with [ and finishes with ])
 */
// Do not print the $buffer
global $wp_super_cache_comments;
$wp_super_cache_comments = false;
\PoP\Root\App::addFilter('wp_cache_eof_tags', 'gdWpCacheEofTagsJsonResponse');
function gdWpCacheEofTagsJsonResponse()
{

    // }|]: JSON file can also be cached
    return '/(<\/html>|<\/rss>|<\/feed>|<\/urlset|<\?xml|}|])/i';
}
