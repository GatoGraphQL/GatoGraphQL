<?php
namespace PoP\CMSModel\WP;

class FunctionAPI extends \PoP\CMS\WP\FunctionAPI implements \PoP\CMSModel\FunctionAPI {

	/***********************************************************/
	/** Functions 1 to 1 with WordPress signature */
	/***********************************************************/
	function get_post_status($post_id) {

		return get_post_status($post_id);
	}
	function get_posts($query) {

		return get_posts($query);
	}
	function get_post_types($args = array()) {

		return get_post_types($args);
	}
	function get_object_taxonomies($object, $output = 'names') {

		return get_object_taxonomies($object, $output);
	}
	function get_page_by_path($page_path, $output = OBJECT, $post_type = 'page') {

		return get_page_by_path($page_path, $output, $post_type);
	}
	function get_userdata($user_id) {

		return get_userdata($user_id);
	}
	function get_comments($query) {

		return get_comments($query);
	}
	function get_comment($comment_id) {

		return get_comment($comment_id);
	}
	function get_post($post_id) {

		return get_post($post_id);
	}
	function get_tag($tag_id) {

		return get_tag($tag_id);
	}
	function get_nav_menu_locations() {

		return get_nav_menu_locations();
	}
	function wp_get_nav_menu_object($menu_object_id) {

		return wp_get_nav_menu_object($menu_object_id);
	}
	function get_tags($query) {

		return get_tags($query);
	}
	function get_user_by($field, $value) {

		return get_user_by($field, $value);
	}
	function get_users($query) {

		return get_users($query);
	}
	function get_the_author_meta($field = '', $user_id = false) {

		return get_the_author_meta($field, $user_id);
	}
	function get_author_posts_url($user_id) {

		return get_author_posts_url($user_id);
	}
	function wp_get_nav_menu_items($menu) {

		return wp_get_nav_menu_items($menu);
	}
	function get_post_type($post) {

		return get_post_type($post);
	}
	function get_comments_number($post_id) {

		return get_comments_number($post_id);
	}
	function get_the_category($post_id = false) {

		return get_the_category($post_id);
	}
	function wp_get_post_categories($post_id = 0, $args = array()) {

		return wp_get_post_categories($post_id, $args);
	}
	function wp_get_post_tags($post_id = 0, $args = array()) {

		return wp_get_post_tags($post_id, $args);
	}
	function wp_get_object_terms($object_ids, $taxonomies, $args = array()) {

		return wp_get_object_terms($object_ids, $taxonomies, $args);
	}
	function get_permalink($post_id) {

		return get_permalink($post_id);
	}
	function get_the_excerpt($post_id) {

		return get_the_excerpt($post_id);
	}
	function has_post_thumbnail($post_id) {

		return has_post_thumbnail($post_id);
	}
	function get_post_thumbnail_id($post_id) {

		return get_post_thumbnail_id($post_id);
	}
	function wp_get_attachment_image_src($image_id, $size = null) {

		return wp_get_attachment_image_src($image_id, $size);
	}
	function get_edit_post_link($post_id) {

		return get_edit_post_link($post_id);
	}
	function get_delete_post_link($post_id) {

		return get_delete_post_link($post_id);
	}
	function get_tag_link($tag_id) {

		return get_tag_link($tag_id);
	}
	function get_post_mime_type($post_thumb_id) {

		return get_post_mime_type($post_thumb_id);
	}
	function get_the_title($post = 0) {

		return get_the_title($post);
	}
	function get_bloginfo($show = '', $filter = 'raw') {

		return get_bloginfo($show, $filter);
	}
	function get_single_post_title($post) {

		// Copied from `single_post_title` in wp-includes/general-template.php
		return apply_filters( 'single_post_title', $post->post_title, $post);
	}
	function get_search_query($escaped = true) {

		return get_search_query($escaped);
	}
	function get_cat_title($cat) {

		// Copied from `single_term_title` in wp-includes/general-template.php
		return apply_filters('single_cat_title', $cat->name);
	}
	function get_tag_title($tag) {

		// Copied from `single_term_title` in wp-includes/general-template.php
		return apply_filters('single_tag_title', $tag->name);
	}
	function get_query_var($var, $default = '') {

		return get_query_var($var, $default);
	}
	function home_url($path = '', $scheme = null) {

		return home_url($path, $scheme);
	}
	function get_terms($args = array()) {

		return get_terms($args);
	}
	function get_query_from_request_uri() {

		// From the new URI set in $_SERVER['REQUEST_URI'], re-generate $vars
		$wp = new WP();
		$wp->parse_request();
		return new WP_Query($wp->query_vars);
	}

	function is_user_logged_in() {

		return is_user_logged_in();
	}
	function get_current_user() {

		return wp_get_current_user();
	}
	function get_current_user_id() {

		return get_current_user_id();
	}
	function get_the_user_role($user_id) {

		return get_the_user_role($user_id);
	}
	function get_home_static_page() {

		if (get_option('show_on_front') == 'page') {
			
			$static_page_id = (int)get_option('page_on_front');
			return $static_page_id > 0 ? $static_page_id : null;
		}

		return null;
	}
	function logout() {

		wp_logout();

		// Delete the current user, so that it already says "user not logged in" for the toplevel feedback
		global $current_user;
		$current_user = null;
		wp_set_current_user(0);
	}
	function insert_comment($comment_data) {

		return wp_insert_comment($comment_data);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new FunctionAPI();
