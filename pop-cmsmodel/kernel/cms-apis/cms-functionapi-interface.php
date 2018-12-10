<?php

interface PoP_CMSModel_CMS_FunctionAPI extends PoP_CMS_FunctionAPI {

	/***********************************************************/
	/** Most functions below are 1 to 1 with WordPress signature */
	/***********************************************************/
	function get_post_status($post_id);
	function get_posts($query);
	function get_post_types($args = array());
	function get_object_taxonomies($object, $output = 'names');
	function get_page_by_path($page_path, $output = OBJECT, $post_type = 'page');
	function get_userdata($user_id);
	function get_comments($query);
	function get_comment($comment_id);
	function get_post($post_id);
	function get_tag($tag_id);
	function get_nav_menu_locations();
	function wp_get_nav_menu_object($menu_object_id);
	function get_tags($query);
	function get_user_by($field, $value);
	function get_users($query);
	function get_the_author_meta($field = '', $user_id = false);
	function get_author_posts_url($user_id);
	function wp_get_nav_menu_items($menu);
	function get_post_type($post);
	function get_comments_number($post_id);
	function get_the_category($post_id = false);
	function wp_get_post_categories($post_id = 0, $args = array());
	function wp_get_post_tags($post_id = 0, $args = array());
	function wp_get_object_terms($object_ids, $taxonomies, $args = array());
	function get_permalink($post_id);
	function get_the_excerpt($post_id);
	function has_post_thumbnail($post_id);
	function get_post_thumbnail_id($post_id);
	function wp_get_attachment_image_src($image_id, $size = null);
	function get_edit_post_link($post_id);
	function get_delete_post_link($post_id);
	function get_tag_link($tag_id);
	function get_post_mime_type($post_thumb_id);
	function get_the_title($post = 0);
	function get_bloginfo($show = '', $filter = 'raw');
	function get_single_post_title($post);
	function get_search_query($escaped = true);
	function get_cat_title($cat);
	function get_tag_title($tag);
	function get_query_var($var, $default = '');
	function home_url($path = '', $scheme = null);
	function get_terms($args = array());
	function get_query_from_request_uri();
	function is_user_logged_in();
	function get_current_user();
	function get_current_user_id();
	function get_the_user_role($user_id);
	function get_home_static_page();
}
