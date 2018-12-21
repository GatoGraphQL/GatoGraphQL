<?php
namespace PoP\CMS\WP;

class FunctionAPI extends \PoP\CMS\FunctionAPI_Base implements \PoP\CMS\FunctionAPI {

	/***********************************************************/
	/** Most functions below are 1 to 1 with WordPress signature */
	/***********************************************************/
	function get_option($option) {

		return get_option($option);
	}
	function get_post_meta($post_id, $key = '', $single = false) {

		return get_post_meta($post_id, $key, $single);
	}
	function delete_post_meta($post_id, $meta_key, $meta_value = '') {

		return delete_post_meta($post_id, $meta_key, $meta_value);
	}
	function add_post_meta($post_id, $meta_key, $meta_value, $unique = false) {

		return add_post_meta($post_id, $meta_key, $meta_value, $unique);
	}
	function get_term_meta($term_id, $key = '', $single = false) {

		return get_term_meta($term_id, $key, $single);
	}
	function delete_term_meta($term_id, $meta_key, $meta_value = '') {

		return delete_term_meta($term_id, $meta_key, $meta_value);
	}
	function add_term_meta($term_id, $meta_key, $meta_value, $unique = false) {

		return add_term_meta($term_id, $meta_key, $meta_value, $unique);
	}
	function get_user_meta($user_id, $key = '', $single = false) {

		return get_user_meta($user_id, $key, $single);
	}
	function delete_user_meta($user_id, $meta_key, $meta_value = '') {

		return delete_user_meta($user_id, $meta_key, $meta_value);
	}
	function add_user_meta($user_id, $meta_key, $meta_value, $unique = false) {

		return add_user_meta($user_id, $meta_key, $meta_value, $unique);
	}
	function get_comment_meta($comment_id, $key = '', $single = false) {

		return get_comment_meta($comment_id, $key, $single);
	}
	function delete_comment_meta($comment_id, $meta_key, $meta_value = '') {

		return delete_comment_meta($comment_id, $meta_key, $meta_value);
	}
	function add_comment_meta($comment_id, $meta_key, $meta_value, $unique = false) {

		return add_comment_meta($comment_id, $meta_key, $meta_value, $unique);
	}
	function redirect($url) {

		wp_redirect($url);
	}
	function get_global_query() {

		global $wp_query;
		return $wp_query;
	}
	function query_is_hierarchy($query, $hierarchy) {

		// Template hierarchy
		if ($hierarchy == GD_SETTINGS_HIERARCHY_HOME) {

			return $query->is_home();
		}
		elseif ($hierarchy == GD_SETTINGS_HIERARCHY_FRONTPAGE) {

			return $query->is_front_page();
		}
		elseif ($hierarchy == GD_SETTINGS_HIERARCHY_TAG) {

			return $query->is_tag();
		}
		elseif ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE) {
			
			return $query->is_page();
		}
		elseif ($hierarchy == GD_SETTINGS_HIERARCHY_SINGLE) {
			
			return $query->is_single();
		}
		elseif ($hierarchy == GD_SETTINGS_HIERARCHY_AUTHOR) {
			
			return $query->is_author();
		}
		elseif ($hierarchy == GD_SETTINGS_HIERARCHY_404) {
			
			return $query->is_404();
		}
		elseif ($hierarchy == GD_SETTINGS_HIERARCHY_SEARCH) {

			return $query->is_search();
		}
		elseif ($hierarchy == GD_SETTINGS_HIERARCHY_CATEGORY) {

			return $query->is_category();
		}		
		elseif ($hierarchy == GD_SETTINGS_HIERARCHY_ARCHIVE) {

			return $query->is_archive();
		}

		return false;
	}

	function get_site_name() {

		return get_bloginfo('name');
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new FunctionAPI();
