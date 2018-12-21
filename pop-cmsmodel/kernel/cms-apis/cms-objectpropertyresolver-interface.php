<?php
namespace PoP\CMSModel;

interface ObjectPropertyResolver extends \PoP\CMS\ObjectPropertyResolver {

	/***********************************************************/
	/** Functions to access object properties */
	/***********************************************************/
	function get_menu_object_term_id($menu_object);
	function get_post_id($post);
	function get_comment_content($comment);
	function get_comment_user_id($comment);
	function get_comment_post_id($comment);
	function get_comment_approved($comment);
	function get_comment_type($comment);
	function get_comment_parent($comment);
	function get_comment_date_gmt($comment);
	function get_comment_id($comment);
	function get_menu_item_title($menu_item);
	function get_menu_item_object_id($menu_item);
	function get_menu_item_url($menu_item);
	function get_menu_item_classes($menu_item);
	function get_menu_item_id($menu_item);
	function get_menu_item_parent($menu_item);
	function get_menu_item_target($menu_item);
	function get_menu_item_description($menu_item);
	function get_menu_term_id($menu);
	function get_post_type($post);
	function get_category_term_id($cat);
	function get_post_title($post);
	function get_post_content($post);
	function get_post_author($post);
	function get_post_date($post);
	function get_tag_name($tag);
	function get_tag_slug($tag);
	function get_tag_term_group($tag);
	function get_tag_term_taxonomy_id($tag);
	function get_tag_taxonomy($tag);
	function get_tag_description($tag);
	function get_tag_parent($tag);
	function get_tag_count($tag);
	function get_tag_term_id($tag);
	function get_user_roles($user);
	function get_user_login($user);
	function get_user_nicename($user);
	function get_user_display_name($user);
	function get_user_firstname($user);
	function get_user_lastname($user);
	function get_user_email($user);
	function get_user_id($user);
	function get_user_description($user);
	function get_user_url($user);
	function get_taxonomy_hierarchical($taxonomy);
	function get_taxonomy_name($taxonomy);
}
