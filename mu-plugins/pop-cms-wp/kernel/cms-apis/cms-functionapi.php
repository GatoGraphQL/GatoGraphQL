<?php
namespace PoP\CMS\WP;

class FunctionAPI extends \PoP\CMS\FunctionAPI_Base implements \PoP\CMS\FunctionAPI
{

    public function getOption($option, $default = false)
    {
        return get_option($option, $default);
    }
    public function redirect($url)
    {
        wp_redirect($url);
    }

    public function getSiteName()
    {
        return get_bloginfo('name');
    }

    public function getSiteDescription()
    {
        return get_bloginfo('description');
    }

    public function getAdminUserEmail()
    {
        return get_bloginfo('admin_email');
    }

    public function getVersion()
    {
        return get_bloginfo('version');
    }

    public function getHomeURL()
    {
        return home_url();
    }

    public function getSiteURL()
    {
        return get_site_url();
    }

    public function getErrorClass()
    {
        return \WP_Error::class;
    }


    public function getPostMeta($post_id, $key = '', $single = false)
    {
        return get_post_meta($post_id, $key, $single);
    }
    public function deletePostMeta($post_id, $meta_key, $meta_value = '')
    {
        return delete_post_meta($post_id, $meta_key, $meta_value);
    }
    public function addPostMeta($post_id, $meta_key, $meta_value, $unique = false)
    {
        return add_post_meta($post_id, $meta_key, $meta_value, $unique);
    }
    public function getTermMeta($term_id, $key = '', $single = false)
    {
        return get_term_meta($term_id, $key, $single);
    }
    public function deleteTermMeta($term_id, $meta_key, $meta_value = '')
    {
        return delete_term_meta($term_id, $meta_key, $meta_value);
    }
    public function addTermMeta($term_id, $meta_key, $meta_value, $unique = false)
    {
        return add_term_meta($term_id, $meta_key, $meta_value, $unique);
    }
    public function getUserMeta($user_id, $key = '', $single = false)
    {
        return get_user_meta($user_id, $key, $single);
    }
    public function deleteUserMeta($user_id, $meta_key, $meta_value = '')
    {
        return delete_user_meta($user_id, $meta_key, $meta_value);
    }
    public function addUserMeta($user_id, $meta_key, $meta_value, $unique = false)
    {
        return add_user_meta($user_id, $meta_key, $meta_value, $unique);
    }
    public function getCommentMeta($comment_id, $key = '', $single = false)
    {
        return get_comment_meta($comment_id, $key, $single);
    }
    public function deleteCommentMeta($comment_id, $meta_key, $meta_value = '')
    {
        return delete_comment_meta($comment_id, $meta_key, $meta_value);
    }
    public function addCommentMeta($comment_id, $meta_key, $meta_value, $unique = false)
    {
        return add_comment_meta($comment_id, $meta_key, $meta_value, $unique);
    }
    public function getGlobalQuery()
    {
        global $wp_query;
        return $wp_query;
    }
    public function queryIsHierarchy($query, $hierarchy)
    {

        // Template hierarchy
        if ($hierarchy == GD_SETTINGS_HIERARCHY_HOME) {
            return $query->is_home();
        } elseif ($hierarchy == GD_SETTINGS_HIERARCHY_FRONTPAGE) {
            return $query->is_front_page();
        } elseif ($hierarchy == GD_SETTINGS_HIERARCHY_TAG) {
            return $query->is_tag();
        } elseif ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE) {
            return $query->is_page();
        } elseif ($hierarchy == GD_SETTINGS_HIERARCHY_SINGLE) {
            return $query->is_single();
        } elseif ($hierarchy == GD_SETTINGS_HIERARCHY_AUTHOR) {
            return $query->is_author();
        } elseif ($hierarchy == GD_SETTINGS_HIERARCHY_404) {
            return $query->is_404();
        } elseif ($hierarchy == GD_SETTINGS_HIERARCHY_SEARCH) {
            return $query->is_search();
        } elseif ($hierarchy == GD_SETTINGS_HIERARCHY_CATEGORY) {
            return $query->is_category();
        } elseif ($hierarchy == GD_SETTINGS_HIERARCHY_ARCHIVE) {
            return $query->is_archive();
        }

        return false;
    }
}

/**
 * Initialize
 */
new FunctionAPI();
