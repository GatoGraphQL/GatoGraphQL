<?php
namespace PoP\CMS;

interface FunctionAPI {

	/***********************************************************/
	/** Most functions below are 1 to 1 with WordPress signature */
	/***********************************************************/
	function get_option($option);
	function get_post_meta($post_id, $key = '', $single = false);
	function delete_post_meta($post_id, $meta_key, $meta_value = '');
	function add_post_meta($post_id, $meta_key, $meta_value, $unique = false);
	function get_term_meta($term_id, $key = '', $single = false);
	function delete_term_meta($term_id, $meta_key, $meta_value = '');
	function add_term_meta($term_id, $meta_key, $meta_value, $unique = false);
	function get_user_meta($user_id, $key = '', $single = false);
	function delete_user_meta($user_id, $meta_key, $meta_value = '');
	function add_user_meta($user_id, $meta_key, $meta_value, $unique = false);
	function get_comment_meta($comment_id, $key = '', $single = false);
	function delete_comment_meta($comment_id, $meta_key, $meta_value = '');
	function add_comment_meta($comment_id, $meta_key, $meta_value, $unique = false);
	function redirect($url);
	function get_global_query();
	function query_is_hierarchy($query, $hierarchy);
	function get_site_name();
	function get_error_class();
}