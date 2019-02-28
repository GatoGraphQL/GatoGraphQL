<?php
namespace PoP\CMSModel\WP;

class FunctionAPI extends \PoP\CMS\WP\FunctionAPI implements \PoP\CMSModel\FunctionAPI
{

    /**
     * Functions 1 to 1 with WordPress signature
     */
    public function getPostStatus($post_id)
    {
        return get_post_status($post_id);
    }
    public function getPosts($query)
    {
        return get_posts($query);
    }
    public function getPostTypes($args = array())
    {
        return get_post_types($args);
    }
    public function getObjectTaxonomies($object, $output = 'names')
    {
        return get_object_taxonomies($object, $output);
    }
    public function getPageByPath($page_path, $output = OBJECT, $post_type = 'page')
    {
        return get_page_by_path($page_path, $output, $post_type);
    }
    public function getUserdata($user_id)
    {
        return get_userdata($user_id);
    }
    public function getComments($query)
    {
        return get_comments($query);
    }
    public function getComment($comment_id)
    {
        return get_comment($comment_id);
    }
    public function getPost($post_id)
    {
        return get_post($post_id);
    }
    public function getTag($tag_id)
    {
        return get_tag($tag_id);
    }
    public function getNavMenuLocations()
    {
        return get_nav_menu_locations();
    }
    public function wpGetNavMenuObject($menu_object_id)
    {
        return wp_get_nav_menu_object($menu_object_id);
    }
    public function addRole($role, $display_name, $capabilities = array())
    {
        add_role($role, $display_name, $capabilities);
    }
    public function getTags($query)
    {
        return get_tags($query);
    }
    public function getUserBy($field, $value)
    {
        return get_user_by($field, $value);
    }
    public function getUsers($query)
    {
        return get_users($query);
    }
    public function getTheAuthorMeta($field = '', $user_id = false)
    {
        return get_the_author_meta($field, $user_id);
    }
    public function getAuthorPostsUrl($user_id)
    {
        return get_author_posts_url($user_id);
    }
    public function wpGetNavMenuItems($menu)
    {
        return wp_get_nav_menu_items($menu);
    }
    public function getPostType($post)
    {
        return get_post_type($post);
    }
    public function getCommentsNumber($post_id)
    {
        return get_comments_number($post_id);
    }
    public function getTheCategory($post_id = false)
    {
        return get_the_category($post_id);
    }
    public function wpGetPostCategories($post_id = 0, $args = array())
    {
        return wp_get_post_categories($post_id, $args);
    }
    public function wpGetPostTags($post_id = 0, $args = array())
    {
        return wp_get_post_tags($post_id, $args);
    }
    public function wpGetObjectTerms($object_ids, $taxonomies, $args = array())
    {
        return wp_get_object_terms($object_ids, $taxonomies, $args);
    }
    public function getPermalink($post_id)
    {
        return get_permalink($post_id);
    }
    public function getTheExcerpt($post_id)
    {
        return get_the_excerpt($post_id);
    }
    public function hasPostThumbnail($post_id)
    {
        return has_post_thumbnail($post_id);
    }
    public function getPostThumbnailId($post_id)
    {
        return get_post_thumbnail_id($post_id);
    }
    public function wpGetAttachmentImageSrc($image_id, $size = null)
    {
        return wp_get_attachment_image_src($image_id, $size);
    }
    public function getEditPostLink($post_id)
    {
        return get_edit_post_link($post_id);
    }
    public function getDeletePostLink($post_id)
    {
        return get_delete_post_link($post_id);
    }
    public function getTagLink($tag_id)
    {
        return get_tag_link($tag_id);
    }
    public function getPostMimeType($post_thumb_id)
    {
        return get_post_mime_type($post_thumb_id);
    }
    public function getTheTitle($post = 0)
    {
        return get_the_title($post);
    }
    public function getBloginfo($show = '', $filter = 'raw')
    {
        return get_bloginfo($show, $filter);
    }
    public function getSinglePostTitle($post)
    {

        // Copied from `single_post_title` in wp-includes/general-template.php
        return apply_filters('single_post_title', $post->post_title, $post);
    }
    public function getSearchQuery($escaped = true)
    {
        return get_search_query($escaped);
    }
    public function getCatTitle($cat)
    {

        // Copied from `single_term_title` in wp-includes/general-template.php
        return apply_filters('single_cat_title', $cat->name);
    }
    public function getTagTitle($tag)
    {

        // Copied from `single_term_title` in wp-includes/general-template.php
        return apply_filters('single_tag_title', $tag->name);
    }
    public function getQueryVar($var, $default = '')
    {
        return get_query_var($var, $default);
    }
    public function homeUrl($path = '', $scheme = null)
    {
        return home_url($path, $scheme);
    }
    public function getTerms($args = array())
    {
        return get_terms($args);
    }
    public function getQueryFromRequestUri()
    {

        // From the new URI set in $_SERVER['REQUEST_URI'], re-generate $vars
        $wp = new WP();
        $wp->parse_request();
        return new WP_Query($wp->query_vars);
    }

    public function isUserLoggedIn()
    {
        return is_user_logged_in();
    }
    public function getCurrentUser()
    {
        return wp_get_current_user();
    }
    public function getCurrentUserId()
    {
        return get_current_user_id();
    }
    public function getTheUserRole($user_id)
    {
        return get_the_user_role($user_id);
    }
    public function getHomeStaticPage()
    {
        if (get_option('show_on_front') == 'page') {
            $static_page_id = (int)get_option('page_on_front');
            return $static_page_id > 0 ? $static_page_id : null;
        }

        return null;
    }
    public function logout()
    {
        wp_logout();

        // Delete the current user, so that it already says "user not logged in" for the toplevel feedback
        global $current_user;
        $current_user = null;
        wp_set_current_user(0);
    }
    public function insertComment($comment_data)
    {
        return wp_insert_comment($comment_data);
    }
    public function getAllowedPostTags()
    {
        global $allowedposttags;
        return $allowedposttags;
    }
}

/**
 * Initialize
 */
new FunctionAPI();
